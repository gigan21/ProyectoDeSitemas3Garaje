// Versión simplificada para depuración
console.log('Script QR cargado');

// Función simple para probar
function testQRScanner() {
    console.log('Función testQRScanner llamada');
    
    // Verificar si el modal existe
    const modal = document.getElementById('qrScannerModal');
    if (!modal) {
        console.error('Modal no encontrado');
        return;
    }
    
    console.log('Modal encontrado, abriendo...');
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Intentar iniciar cámara después de que se abra el modal
    setTimeout(() => {
        console.log('Intentando iniciar cámara...');
        startCameraSimple();
    }, 500);
}

// Función simple para iniciar cámara
async function startCameraSimple() {
    try {
        console.log('Solicitando acceso a cámara...');
        const stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });
        
        console.log('Cámara obtenida exitosamente');
        const video = document.getElementById('qrVideo');
        if (video) {
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('cameraPlaceholder').style.display = 'none';
            console.log('Video configurado');
        } else {
            console.error('Elemento video no encontrado');
        }
        
    } catch (error) {
        console.error('Error al acceder a la cámara:', error);
        alert('Error al acceder a la cámara: ' + error.message);
    }
}

// Función global para abrir el modal desde los botones Gratis
function openQRScanner(espacioId) {
    console.log('openQRScanner llamado con ID:', espacioId);
    testQRScanner();
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando QR scanner...');
    
    // Verificar elementos
    console.log('Modal existe:', !!document.getElementById('qrScannerModal'));
    console.log('Video existe:', !!document.getElementById('qrVideo'));
    console.log('Placeholder existe:', !!document.getElementById('cameraPlaceholder'));
});
