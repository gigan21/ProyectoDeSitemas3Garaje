# Códigos QR de Prueba para GarajeAlfaro

## Ticket 1 - Descuento Aplicable (120 Bs)
```json
{"ticket": "TK001", "monto": 120}
```

## Ticket 2 - Descuento No Aplicable (99 Bs)
```json
{"ticket": "TK002", "monto": 99}
```

## Instrucciones para generar los códigos QR:

1. **Usar un generador QR online** como:
   - https://www.qr-code-generator.com/
   - https://qr-generator.com/
   - https://www.the-qrcode-generator.com/

2. **Copiar y pegar el JSON** correspondiente en el generador

3. **Descargar la imagen** del código QR generado

4. **Imprimir o mostrar en pantalla** para probar el escáner

## Cómo probar:

1. Asegúrate de que tienes un espacio ocupado por un cliente ocasional
2. Haz clic en el botón "Gratis" de ese espacio
3. Permite el acceso a la cámara cuando se solicite
4. Escanea el código QR del Ticket 1 (120 Bs) - debería aplicar el descuento
5. Escanea el código QR del Ticket 2 (99 Bs) - debería mostrar "Descuento no aplicable"

## Notas técnicas:

- El sistema funciona con `getUserMedia` para acceder a la cámara
- Compatible con entorno local (XAMPP) sin HTTPS
- Usa la librería jsQR para la detección
- Los datos se envían via AJAX al backend Laravel
- La validación se hace tanto en frontend como backend
