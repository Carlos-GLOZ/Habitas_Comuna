from PIL import Image
import os

# Abre la imagen
filename = "imagen.jpg"
image = Image.open(filename)

# Crea un directorio para las imágenes redimensionadas
if not os.path.exists("resized"):
    os.makedirs("resized")

# Crea una lista de resoluciones deseadas
resolutions = [(72, 72), (800, 600), (1024, 768)]

# Redimensiona la imagen para cada resolución y guárdala en un archivo separado
for width, height in resolutions:
    # Redimensiona la imagen
    resized_image = image.resize((width, height), Image.ANTIALIAS)

    # Crea el nombre del archivo de salida
    output_filename = f"resized/{width}x{height}_{filename}"

    # Guarda la imagen redimensionada en un archivo
    resized_image.save(output_filename)
