# Documentación de Comandos de Integración con OpenAI

Esta documentación describe un conjunto de comandos Artisan diseñados para interactuar con la API de OpenAI para el procesamiento de documentos e IA conversacional.

## Descripción General de Comandos

### 1. `upload`
Sube un archivo PDF a OpenAI y lo convierte en un almacén de vectores.

**Uso:**
```bash
php artisan upload
```

**Proceso:**
1. Lee la clave API de OpenAI desde la configuración
2. Sube el archivo PDF (`colombia.pdf`) al almacenamiento de OpenAI
3. Crea un almacén de vectores a partir del archivo subido
4. Devuelve el ID del almacén de vectores

**Variables Importantes:**
- `$name`: Establecido como "Solvo Colombia 2025"
- Ubicación del archivo: `public/colombia.pdf`

### 2. `create_thread`
Crea un nuevo hilo de conversación con el asistente de OpenAI.

**Uso:**
```bash
php artisan create_thread
```

**Proceso:**
1. Inicializa el cliente de OpenAI
2. Crea un nuevo hilo
3. Devuelve el ID del hilo

### 3. `create_assistant`
Crea un nuevo asistente de OpenAI con instrucciones específicas y acceso al PDF.

**Uso:**
```bash
php artisan create_assistant
```

**Configuración:**
- Nombre del Asistente: "Solvo Colombia 2025"
- Modelo: gpt-3.5-turbo-0125
- Herramientas: búsqueda de archivos habilitada
- Integración del Almacén de Vectores: Configurado con ID específico

**Áreas de Especialización:**
- Procesos Administrativos y Compensación
- Deserción y Retención
- Bienestar
- Administración de Ciberseguridad e Instalaciones
- HR Vensure
- Tecnología de la Información
- Marketing
- Operaciones
- Personas y Cultura
- Pasantías Solvo
- Solvo Social
- Adquisición de Talento
- Relaciones Laborales y Capacitación

### 4. `chat`
Inicia una conversación con el asistente utilizando IDs específicos de hilo y asistente.

**Uso:**
```bash
php artisan chat
```

**Proceso:**
1. Crea un nuevo mensaje en el hilo especificado
2. Inicia una ejecución con el asistente
3. Espera la finalización de la respuesta
4. Recupera y muestra el último mensaje
5. Muestra el total de tokens utilizados

**Configuración Predeterminada:**
- ID del Hilo: `thread_Han3NyplDGUlA182DtshqCSu`
- ID del Asistente: `asst_rhx8mjhKKFiVR08GtUM2jQvW`

### 5. `inspire`
Muestra una frase inspiradora.

**Uso:**
```bash
php artisan inspire
```

**Programación:** Se ejecuta cada hora

## Orden de Ejecución

Para una configuración y uso correctos, ejecute los comandos en el siguiente orden:

1. `upload` - Crear el almacén de vectores desde el PDF
2. `create_thread` - Inicializar un hilo de conversación
3. `create_assistant` - Configurar el asistente de IA
4. `chat` - Comenzar la interacción con el asistente

## Notas Importantes

- Todos los comandos requieren una clave API válida de OpenAI configurada en la aplicación
- El comando chat incluye un mecanismo de espera con un intervalo de 3 segundos
- El asistente está específicamente diseñado para responder basándose en el contenido del PDF cargado
- El asistente preguntará por el área específica de interés antes de proporcionar respuestas

### Consideraciones Técnicas
- Los comandos deben ejecutarse en un entorno con PHP y Laravel instalados
- Es necesario tener configurado correctamente el archivo de entorno (.env) con la clave API de OpenAI
- El sistema debe tener permisos de lectura/escritura en el directorio público para el manejo de archivos
