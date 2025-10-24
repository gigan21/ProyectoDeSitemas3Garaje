/**
 * Escáner QR para el sistema GarajeAlfaro
 * Utiliza jsQR para la detección de códigos QR desde la cámara
 */

class QRScanner {
    constructor() {
        this.video = null;
        this.canvas = null;
        this.context = null;
        this.stream = null;
        this.scanning = false;
        this.currentEspacioId = null;
        this.scanInterval = null;
        
        this.init();
    }

    init() {
        this.video = document.getElementById('qrVideo');
        this.canvas = document.getElementById('qrCanvas');
        this.context = this.canvas.getContext('2d');
        
        // Event listeners para el modal
        this.setupModalEvents();
        
        // Event listeners para los botones
        this.setupButtonEvents();
    }

    setupModalEvents() {
        const modal = document.getElementById('qrScannerModal');
        
        modal.addEventListener('show.bs.modal', (event) => {
            console.log('Modal abriéndose...');
            // Si no hay relatedTarget (cuando se abre programáticamente), usar el ID guardado
            if (event.relatedTarget) {
                this.currentEspacioId = event.relatedTarget.getAttribute('data-espacio-id');
                console.log('ID obtenido desde relatedTarget:', this.currentEspacioId);
            }
            // Si ya tenemos el ID (desde openQRScanner), usarlo
            if (this.currentEspacioId) {
                console.log('Iniciando cámara para espacio:', this.currentEspacioId);
                this.startCamera();
            } else {
                console.error('No se encontró ID del espacio');
            }
        });

        modal.addEventListener('hide.bs.modal', () => {
            this.stopCamera();
            this.resetModal();
        });
    }

    setupButtonEvents() {
        // Botón reintentar
        document.getElementById('retryScanBtn').addEventListener('click', () => {
            this.resetModal();
            this.startScanning();
        });

        // Botón procesar ticket
        document.getElementById('processTicketBtn').addEventListener('click', () => {
            this.processTicket();
        });
    }

    async startCamera() {
        console.log('Iniciando cámara...');
        try {
            // Solicitar acceso a la cámara
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment', // Cámara trasera si está disponible
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });

            console.log('Cámara obtenida exitosamente');
            this.video.srcObject = this.stream;
            
            this.video.addEventListener('loadedmetadata', () => {
                console.log('Video cargado, iniciando reproducción');
                this.video.play();
                this.startScanning();
            });

            // Ocultar placeholder y mostrar video
            document.getElementById('cameraPlaceholder').style.display = 'none';
            this.video.style.display = 'block';

        } catch (error) {
            console.error('Error al acceder a la cámara:', error);
            this.showError('No se pudo acceder a la cámara. Verifica los permisos.');
        }
    }

    stopCamera() {
        this.scanning = false;
        
        if (this.scanInterval) {
            clearInterval(this.scanInterval);
            this.scanInterval = null;
        }

        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }

        if (this.video) {
            this.video.srcObject = null;
            this.video.style.display = 'none';
        }

        document.getElementById('cameraPlaceholder').style.display = 'block';
    }

    startScanning() {
        this.scanning = true;
        
        this.scanInterval = setInterval(() => {
            if (this.scanning && this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
                this.scanQR();
            }
        }, 100); // Escanear cada 100ms
    }

    scanQR() {
        // Dibujar el frame actual del video en el canvas
        this.context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        
        // Obtener los datos de imagen del canvas
        const imageData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height);
        
        // Intentar detectar el código QR
        const code = jsQR(imageData.data, imageData.width, imageData.height);
        
        if (code) {
            this.handleQRDetected(code.data);
        }
    }

    handleQRDetected(qrData) {
        this.scanning = false;
        
        try {
            // Intentar parsear el JSON del QR
            const ticketData = JSON.parse(qrData);
            
            if (this.validateTicketData(ticketData)) {
                this.showTicketInfo(ticketData);
            } else {
                this.showError('Formato de ticket inválido');
            }
            
        } catch (error) {
            console.error('Error al parsear QR:', error);
            this.showError('Código QR inválido');
        }
    }

    validateTicketData(data) {
        return data && 
               typeof data.ticket === 'string' && 
               typeof data.monto === 'number' && 
               data.monto > 0;
    }

    showTicketInfo(ticketData) {
        // Ocultar video y mostrar información del ticket
        this.video.style.display = 'none';
        
        // Mostrar información del ticket
        document.getElementById('ticketNumber').textContent = ticketData.ticket;
        document.getElementById('ticketAmount').textContent = ticketData.monto;
        document.getElementById('ticketInfo').style.display = 'block';
        
        // Mostrar resultado del escaneo
        const resultDiv = document.getElementById('scanResult');
        const resultAlert = document.getElementById('resultAlert');
        const resultIcon = document.getElementById('resultIcon');
        const resultTitle = document.getElementById('resultTitle');
        const resultMessage = document.getElementById('resultMessage');
        
        resultDiv.style.display = 'block';
        
        if (ticketData.monto >= 100) {
            // Ticket válido para descuento
            resultAlert.className = 'alert scan-success';
            resultIcon.className = 'fas fa-check-circle fa-2x me-3';
            resultTitle.textContent = 'Ticket Válido';
            resultMessage.textContent = `Descuento aplicable (${ticketData.monto} Bs ≥ 100 Bs)`;
            
            // Mostrar botón procesar
            document.getElementById('processTicketBtn').style.display = 'inline-block';
            
        } else {
            // Ticket no válido para descuento
            resultAlert.className = 'alert scan-error';
            resultIcon.className = 'fas fa-times-circle fa-2x me-3';
            resultTitle.textContent = 'Descuento No Aplicable';
            resultMessage.textContent = `Monto insuficiente (${ticketData.monto} Bs < 100 Bs)`;
            
            // Mostrar botón reintentar
            document.getElementById('retryScanBtn').style.display = 'inline-block';
        }
    }

    showError(message) {
        // Ocultar video
        this.video.style.display = 'none';
        
        // Mostrar error
        const resultDiv = document.getElementById('scanResult');
        const resultAlert = document.getElementById('resultAlert');
        const resultIcon = document.getElementById('resultIcon');
        const resultTitle = document.getElementById('resultTitle');
        const resultMessage = document.getElementById('resultMessage');
        
        resultDiv.style.display = 'block';
        resultAlert.className = 'alert scan-error';
        resultIcon.className = 'fas fa-exclamation-triangle fa-2x me-3';
        resultTitle.textContent = 'Error';
        resultMessage.textContent = message;
        
        // Mostrar botón reintentar
        document.getElementById('retryScanBtn').style.display = 'inline-block';
    }

    async processTicket() {
        try {
            const response = await fetch(`/espacios/${this.currentEspacioId}/procesar-qr`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ticket: document.getElementById('ticketNumber').textContent,
                    monto: parseInt(document.getElementById('ticketAmount').textContent)
                })
            });

            const result = await response.json();

            if (result.success) {
                // Mostrar éxito
                this.showSuccess(result.message);
                
                // Cerrar modal después de 2 segundos
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
                    modal.hide();
                    
                    // Recargar la página para actualizar el estado
                    window.location.reload();
                }, 2000);
                
            } else {
                this.showError(result.message || 'Error al procesar el ticket');
            }

        } catch (error) {
            console.error('Error al procesar ticket:', error);
            this.showError('Error de conexión al procesar el ticket');
        }
    }

    showSuccess(message) {
        const resultDiv = document.getElementById('scanResult');
        const resultAlert = document.getElementById('resultAlert');
        const resultIcon = document.getElementById('resultIcon');
        const resultTitle = document.getElementById('resultTitle');
        const resultMessage = document.getElementById('resultMessage');
        
        resultDiv.style.display = 'block';
        resultAlert.className = 'alert scan-success';
        resultIcon.className = 'fas fa-check-circle fa-2x me-3';
        resultTitle.textContent = 'Procesado Exitosamente';
        resultMessage.textContent = message;
        
        // Ocultar botones
        document.getElementById('processTicketBtn').style.display = 'none';
        document.getElementById('retryScanBtn').style.display = 'none';
    }

    resetModal() {
        // Ocultar todos los elementos de resultado
        document.getElementById('scanResult').style.display = 'none';
        document.getElementById('ticketInfo').style.display = 'none';
        
        // Ocultar botones
        document.getElementById('processTicketBtn').style.display = 'none';
        document.getElementById('retryScanBtn').style.display = 'none';
        
        // Mostrar placeholder de cámara
        document.getElementById('cameraPlaceholder').style.display = 'block';
        
        // Resetear variables
        this.scanning = false;
        this.currentEspacioId = null;
    }
}

// Inicializar el escáner cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    window.qrScanner = new QRScanner();
});

// Función global para abrir el modal desde los botones Gratis
function openQRScanner(espacioId) {
    console.log('Abriendo scanner para espacio:', espacioId);
    // Guardar el ID del espacio en la instancia del scanner
    if (window.qrScanner) {
        window.qrScanner.currentEspacioId = espacioId;
        console.log('ID guardado en scanner:', window.qrScanner.currentEspacioId);
    } else {
        console.error('Scanner no inicializado');
    }
    
    const modal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
    modal.show();
}
