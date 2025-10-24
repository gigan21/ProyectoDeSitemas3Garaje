/**
 * Escáner QR Simplificado para GarajeAlfaro
 * Solo funciona con dos códigos QR específicos: 120 Bs y 99 Bs
 */

class SimpleQRScanner {
    constructor() {
        this.video = null;
        this.canvas = null;
        this.context = null;
        this.stream = null;
        this.scanning = false;
        this.currentEspacioId = null;
        this.scanInterval = null;
        
        // Códigos QR esperados (solo estos dos)
        this.expectedCodes = [
            '{"ticket": "TK001", "monto": 120}',  // Descuento aplicable
            '{"ticket": "TK002", "monto": 99}'     // Descuento no aplicable
        ];
        
        this.init();
    }

    init() {
        this.video = document.getElementById('qrVideo');
        this.canvas = document.getElementById('qrCanvas');
        this.context = this.canvas.getContext('2d');
        
        this.setupModalEvents();
        this.setupButtonEvents();
    }

    setupModalEvents() {
        const modal = document.getElementById('qrScannerModal');
        
        modal.addEventListener('show.bs.modal', (event) => {
            console.log('Modal abriéndose...');
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
        const retryBtn = document.getElementById('retryScanBtn');
        if (retryBtn) {
            retryBtn.addEventListener('click', () => {
                this.resetModal();
                this.startScanning();
            });
        }

        // Botón procesar ticket
        const processBtn = document.getElementById('processTicketBtn');
        if (processBtn) {
            processBtn.addEventListener('click', () => {
                this.processTicket();
            });
        }
    }

    async startCamera() {
        console.log('Iniciando cámara...');
        try {
            // Solicitar acceso a la cámara (sin playsinline para compatibilidad)
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
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
            const placeholder = document.getElementById('cameraPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
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

        const placeholder = document.getElementById('cameraPlaceholder');
        if (placeholder) placeholder.style.display = 'block';
    }

    startScanning() {
        this.scanning = true;
        
        this.scanInterval = setInterval(() => {
            if (this.scanning && this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
                this.scanQR();
            }
        }, 200); // Escanear cada 200ms (menos frecuente para mejor rendimiento)
    }

    scanQR() {
        // Dibujar el frame actual del video en el canvas
        this.context.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        
        // Obtener los datos de imagen del canvas
        const imageData = this.context.getImageData(0, 0, this.canvas.width, this.canvas.height);
        
        // Intentar detectar el código QR
        const code = jsQR(imageData.data, imageData.width, imageData.height);
        
        if (code) {
            console.log('QR detectado:', code.data);
            this.handleQRDetected(code.data);
        }
    }

    handleQRDetected(qrData) {
        this.scanning = false;
        
        // Verificar si es uno de los códigos esperados
        if (this.expectedCodes.includes(qrData)) {
            try {
                const ticketData = JSON.parse(qrData);
                this.showTicketInfo(ticketData);
            } catch (error) {
                console.error('Error al parsear QR:', error);
                this.showError('Código QR inválido');
            }
        } else {
            console.log('Código QR no reconocido:', qrData);
            this.showError('Código QR no reconocido. Solo se aceptan los tickets de prueba.');
        }
    }

    showTicketInfo(ticketData) {
        // Ocultar video
        this.video.style.display = 'none';
        
        // Mostrar información del ticket
        const ticketNumber = document.getElementById('ticketNumber');
        const ticketAmount = document.getElementById('ticketAmount');
        const ticketInfo = document.getElementById('ticketInfo');
        
        if (ticketNumber) ticketNumber.textContent = ticketData.ticket;
        if (ticketAmount) ticketAmount.textContent = ticketData.monto;
        if (ticketInfo) ticketInfo.style.display = 'block';
        
        // Mostrar resultado del escaneo
        const resultDiv = document.getElementById('scanResult');
        const resultAlert = document.getElementById('resultAlert');
        const resultIcon = document.getElementById('resultIcon');
        const resultTitle = document.getElementById('resultTitle');
        const resultMessage = document.getElementById('resultMessage');
        
        if (resultDiv) resultDiv.style.display = 'block';
        
        if (ticketData.monto >= 100) {
            // Ticket válido para descuento
            if (resultAlert) resultAlert.className = 'alert scan-success';
            if (resultIcon) resultIcon.className = 'fas fa-check-circle fa-2x me-3';
            if (resultTitle) resultTitle.textContent = 'Ticket Válido';
            if (resultMessage) resultMessage.textContent = `Descuento aplicable (${ticketData.monto} Bs ≥ 100 Bs)`;
            
            // Mostrar botón procesar
            const processBtn = document.getElementById('processTicketBtn');
            if (processBtn) processBtn.style.display = 'inline-block';
            
        } else {
            // Ticket no válido para descuento
            if (resultAlert) resultAlert.className = 'alert scan-error';
            if (resultIcon) resultIcon.className = 'fas fa-times-circle fa-2x me-3';
            if (resultTitle) resultTitle.textContent = 'Descuento No Aplicable';
            if (resultMessage) resultMessage.textContent = `Monto insuficiente (${ticketData.monto} Bs < 100 Bs)`;
            
            // Mostrar botón reintentar
            const retryBtn = document.getElementById('retryScanBtn');
            if (retryBtn) retryBtn.style.display = 'inline-block';
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
        
        if (resultDiv) resultDiv.style.display = 'block';
        if (resultAlert) resultAlert.className = 'alert scan-error';
        if (resultIcon) resultIcon.className = 'fas fa-exclamation-triangle fa-2x me-3';
        if (resultTitle) resultTitle.textContent = 'Error';
        if (resultMessage) resultMessage.textContent = message;
        
        // Mostrar botón reintentar
        const retryBtn = document.getElementById('retryScanBtn');
        if (retryBtn) retryBtn.style.display = 'inline-block';
    }

    async processTicket() {
        try {
            const ticketNumber = document.getElementById('ticketNumber').textContent;
            const ticketAmount = parseInt(document.getElementById('ticketAmount').textContent);
            
            const response = await fetch(`/espacios/${this.currentEspacioId}/procesar-qr`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ticket: ticketNumber,
                    monto: ticketAmount
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                
                // Cerrar modal después de 2 segundos
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('qrScannerModal'));
                    if (modal) modal.hide();
                    
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
        
        if (resultDiv) resultDiv.style.display = 'block';
        if (resultAlert) resultAlert.className = 'alert scan-success';
        if (resultIcon) resultIcon.className = 'fas fa-check-circle fa-2x me-3';
        if (resultTitle) resultTitle.textContent = 'Procesado Exitosamente';
        if (resultMessage) resultMessage.textContent = message;
        
        // Ocultar botones
        const processBtn = document.getElementById('processTicketBtn');
        const retryBtn = document.getElementById('retryScanBtn');
        if (processBtn) processBtn.style.display = 'none';
        if (retryBtn) retryBtn.style.display = 'none';
    }

    resetModal() {
        // Ocultar todos los elementos de resultado
        const scanResult = document.getElementById('scanResult');
        const ticketInfo = document.getElementById('ticketInfo');
        
        if (scanResult) scanResult.style.display = 'none';
        if (ticketInfo) ticketInfo.style.display = 'none';
        
        // Ocultar botones
        const processBtn = document.getElementById('processTicketBtn');
        const retryBtn = document.getElementById('retryScanBtn');
        
        if (processBtn) processBtn.style.display = 'none';
        if (retryBtn) retryBtn.style.display = 'none';
        
        // Mostrar placeholder de cámara
        const placeholder = document.getElementById('cameraPlaceholder');
        if (placeholder) placeholder.style.display = 'block';
        
        // Resetear variables
        this.scanning = false;
    }
}

// Inicializar el escáner cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    window.qrScanner = new SimpleQRScanner();
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
