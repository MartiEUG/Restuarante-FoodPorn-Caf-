# 📸 Cómo Añadir Imágenes a los Platos

## Sistema de Carga de Imágenes

El sistema está completamente preparado para subir y gestionar imágenes de platos. Aquí te explicamos cómo hacerlo:

### 📝 Pasos para Añadir Imágenes

#### 1. **Al Crear un Nuevo Plato:**
   - Ve a **Administración → Platos → Crear Nuevo Plato**
   - Completa los datos del plato (nombre, descripción, precio, categoría)
   - En la sección **"Imagen del Plato"**, haz clic en el campo de archivo
   - Selecciona una imagen de tu computadora (JPG, PNG, GIF o WebP)
   - Máximo 2MB por imagen
   - Haz clic en **"Crear Plato"**

#### 2. **Para Editar un Plato Existente:**
   - Ve a **Administración → Platos**
   - Haz clic en el botón **"Editar"** en el plato que deseas modificar
   - En la sección **"Imagen del Plato"**:
     - Si el plato ya tiene una imagen, la verás en preview
     - Para cambiar la imagen, selecciona una nueva
     - Para mantener la imagen actual, deja el campo vacío
   - Haz clic en **"Actualizar Plato"**

### 🖼️ Requisitos de Imagen

- **Formatos permitidos:** JPG, PNG, GIF, WebP
- **Tamaño máximo:** 2MB
- **Resolución recomendada:** 800x600px o superior
- **Proporción recomendada:** Cuadrada (1:1) o horizontal (4:3)

### 📂 Ubicación de Archivos

Las imágenes se guardan automáticamente en: `/public/images/platos/`

Cada imagen recibe un nombre único automático para evitar conflictos.

### ✅ Visualización de Imágenes

Después de subir una imagen:
- Aparecerá en la **lista de platos** en el panel de administración
- Se mostrará en la vista de gestión con un placeholder si no hay imagen
- Las imágenes se escalan automáticamente para verse bien

### 🔄 Cambiar o Eliminar Imagen

- Para **cambiar una imagen:** Edita el plato y sube una nueva imagen
- Para **mantener la imagen actual:** Deixa el campo de imagen vacío al editar
- La imagen anterior se mantiene en el servidor (no se elimina automáticamente)

### 🐛 Solución de Problemas

**La imagen no se sube:**
- Verifica que el archivo sea menor de 2MB
- Asegúrate de que sea un formato válido (JPG, PNG, GIF, WebP)
- Comprueba que la carpeta `/public/images/platos/` tenga permisos de escritura

**No veo la imagen después de subir:**
- Recarga la página con Ctrl+F5 (limpia caché)
- Verifica que el archivo se haya subido correctamente editando el plato

---

¡Listo! Tu sistema de gestión de imágenes de platos está completamente operacional. 🍽️
