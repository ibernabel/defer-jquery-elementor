# jQuery Defer Safe for Elementor

## 🎯 Descripción

Plugin WordPress que aplica `defer` a jQuery de forma **segura**, mejorando performance sin romper funcionalidad.

### Diferencia clave vs implementación básica:

✅ **Solo aplica defer para visitantes públicos** (no logged-in users)  
✅ Preserva funcionalidad de admin bar  
✅ Compatible con Elementor edit mode  
✅ No afecta AJAX requests  
✅ No rompe customizer  

---

## 📊 Impacto en Performance

### Mejoras observadas en leyesya.com:

```
Mobile Score:  51 → 78 (+27 puntos)
FCP:          3.2s → 2.3s (-900ms, -28%)
LCP:          4.6s → 2.3s (-2.3s, -50%)
TBT:        1140ms → 380ms (-760ms, -67%)
```

**Nota:** Estos resultados son para **visitantes públicos únicamente**.

---

## 🚀 Instalación

### Método 1: Upload manual

1. Descargar el ZIP del plugin
2. WordPress Admin > Plugins > Añadir Nuevo > Subir Plugin
3. Seleccionar ZIP y subir
4. Activar plugin

### Método 2: FTP/File Manager

1. Crear carpeta: `/wp-content/plugins/defer-jquery-safe/`
2. Subir `defer-jquery-safe.php` a esa carpeta
3. WordPress Admin > Plugins > Activar "jQuery Defer Safe for Elementor"

---

## ⚙️ Configuración

**No requiere configuración.** El plugin funciona automáticamente al activarse.

### Condiciones de aplicación:

El defer **SOLO** se aplica cuando:

- ✅ Usuario NO está logueado
- ✅ NO está en modo admin
- ✅ NO está en Elementor edit/preview
- ✅ NO está en customizer
- ✅ NO es request AJAX

Para **usuarios logueados**, jQuery carga normalmente (sin defer) para preservar funcionalidad del admin bar.

---

## 🧪 Testing

### Después de activar:

1. **Limpiar todos los caches**:
   - WP Super Cache > Delete Cache
   - Autoptimize > Delete Cache
   - Browser cache (Ctrl+Shift+Delete)

2. **Test en modo incógnito** (sin login):
   ```
   ✅ Homepage carga correctamente
   ✅ Formularios funcionan
   ✅ Menú mobile funciona
   ✅ No hay errores en Console
   ```

3. **Test como usuario logueado**:
   ```
   ✅ Admin bar funciona
   ✅ Elementor edit mode funciona
   ✅ No hay errores en Console
   ```

4. **Auditoría PageSpeed**:
   ```
   Importante: PageSpeed simula visitante público
   Por lo tanto VERÁ las mejoras de defer
   ```

---

## 🐛 Troubleshooting

### "Veo errores jQuery is not defined cuando estoy logueado"

**Respuesta:** No deberías ver errores cuando estás logueado, porque el plugin NO aplica defer para usuarios logueados.

Si ves errores:
1. Desactiva el plugin
2. Limpia todos los caches
3. Verifica que no haya código defer en functions.php

### "Los visitantes reportan problemas"

Si hay reportes de visitantes públicos con problemas:

1. Desactiva el plugin temporalmente
2. Reporta el issue en GitHub
3. Considera excluir páginas específicas (ver código avanzado abajo)

### "PageSpeed no muestra mejoras"

1. Espera 2-3 minutos después de activar
2. Limpia todos los caches
3. Ejecuta nueva auditoría en modo incógnito
4. Verifica que plugin esté activo

---

## 🔧 Personalización Avanzada

### Excluir páginas específicas:

```php
// Agregar al final de defer-jquery-safe.php, antes del cierre ?>

/**
 * Excluir páginas específicas del defer
 */
add_filter( 'defer_jquery_safe_should_apply', function( $should_apply ) {
    // No aplicar defer en página de contacto
    if ( is_page( 'contacto' ) ) {
        return false;
    }
    
    // No aplicar defer en páginas con formularios complejos
    if ( is_page( array( 'checkout', 'carrito' ) ) ) {
        return false;
    }
    
    return $should_apply;
});
```

### Logging para debugging:

```php
// Agregar después del método should_apply_defer()

public function log_defer_status() {
    if ( current_user_can( 'administrator' ) ) {
        add_action( 'wp_footer', function() {
            echo '<!-- jQuery Defer Status: ' . 
                 ( $this->should_apply_defer() ? 'ACTIVE' : 'DISABLED' ) . 
                 ' -->';
        });
    }
}
```

---

## 📈 Benchmarks

### Ambiente de testing:

- Site: leyesya.com
- Hosting: GoDaddy
- Theme: Hello Elementor
- Plugins: Elementor, MetForm, Autoptimize, WP Super Cache

### Resultados (Mobile):

| Métrica | Sin Plugin | Con Plugin | Mejora |
|---------|------------|------------|--------|
| Score | 51 | 78 | +27 |
| FCP | 3.2s | 2.3s | -28% |
| LCP | 4.6s | 2.3s | -50% |
| TBT | 1140ms | 380ms | -67% |

---

## 🤝 Contribuir

GitHub: https://github.com/ibernabel/defer-jquery-safe

Pull requests son bienvenidos. Para cambios mayores:
1. Abre un issue primero
2. Discute los cambios propuestos
3. Haz fork y crea branch
4. Submit PR con tests

---

## 📄 Licencia

MIT License - Ver LICENSE file

---

## ✉️ Soporte

- GitHub Issues: https://github.com/ibernabel/defer-jquery-safe/issues

---

## 🔄 Changelog

### v1.1.0 (22/10/2025)
- ✨ Mejorado: Solo aplica defer para visitantes públicos
- 🐛 Fix: Usuarios logueados ahora cargan jQuery normalmente
- ✨ Nuevo: Compatibilidad con customizer
- 📝 Documentación extendida

### v1.0.0 (21/10/2025)
- 🎉 Release inicial
- ✨ Defer jQuery básico para frontend

---

## ⚠️ Notas Importantes

1. **Performance vs Funcionalidad**: Este plugin prioriza NO romper funcionalidad. Por eso solo aplica defer para visitantes públicos.

2. **Admin Bar**: Los usuarios logueados NO verán las mejoras de performance, pero tampoco tendrán errores.

3. **PageSpeed testing**: PageSpeed Insights simula un visitante público, por lo tanto SÍ verá las mejoras.

4. **Elementor**: Compatible con edit mode y preview mode.

5. **Cache**: Siempre limpia todos los caches después de activar/desactivar.

---

## 🎯 Próximas Mejoras

- [ ] UI en admin para activar/desactivar
- [ ] Selector de páginas para excluir
- [ ] Estadísticas de performance
- [ ] Modo "aggressive" (opcional)
- [ ] Whitelist de scripts que pueden tener defer

---

**Made with ❤️ for WordPress + Elementor performance optimization**