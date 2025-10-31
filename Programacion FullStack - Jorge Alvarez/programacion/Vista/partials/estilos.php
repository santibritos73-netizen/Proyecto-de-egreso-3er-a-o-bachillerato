<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f9; padding: 20px; }
    .container { max-width: 1400px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    h1, h2, h3 { color: #333; margin-bottom: 20px; }
    
    /* Navegación */
    .nav { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 15px 25px; margin: -30px -30px 30px -30px; border-radius: 12px 12px 0 0; display: flex; align-items: center; justify-content: space-between; }
    .nav-links { display: flex; gap: 20px; }
    .nav a { color: white; text-decoration: none; font-weight: 500; padding: 8px 15px; border-radius: 6px; transition: background 0.3s; }
    .nav a:hover { background-color: rgba(255,255,255,0.2); }
    .nav-user { color: white; font-size: 14px; }
    
    /* Mensajes */
    .mensaje { padding: 15px 20px; margin-bottom: 20px; border-radius: 8px; font-weight: 500; animation: slideDown 0.3s; }
    .mensaje.exito { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745; }
    .mensaje.error { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
    
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Botones */
    button, .btn { background-color: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; text-decoration: none; display: inline-block; }
    button:hover, .btn:hover { background-color: #5568d3; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); }
    .btn-success { background-color: #28a745; }
    .btn-success:hover { background-color: #218838; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4); }
    .btn-danger { background-color: #dc3545; }
    .btn-danger:hover { background-color: #c82333; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4); }
    .btn-secondary { background-color: #6c757d; }
    .btn-secondary:hover { background-color: #5a6268; }
    .btn-sm { padding: 6px 12px; font-size: 12px; }
    
    /* Formularios */
    form { background-color: #f9fafb; padding: 25px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e1e8ed; }
    .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group.full { grid-column: 1 / -1; }
    label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 14px; }
    input[type="text"], input[type="number"], input[type="email"], input[type="tel"], input[type="url"], input[type="date"], select, textarea {
        width: 100%; padding: 12px 15px; border: 1px solid #ced4da; border-radius: 6px; font-size: 14px; transition: border-color 0.3s, box-shadow 0.3s;
    }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    textarea { resize: vertical; min-height: 100px; font-family: inherit; }
    
    /* Tablas */
    table { width: 100%; border-collapse: collapse; margin-top: 20px; background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e9ecef; }
    th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: 600; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
    tr:hover { background-color: #f8f9fa; }
    tr:last-child td { border-bottom: none; }
    
    /* Badges */
    .badge { padding: 5px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .estado-pendiente { background-color: #fff3cd; color: #856404; }
    .estado-en_proceso { background-color: #d1ecf1; color: #0c5460; }
    .estado-completado { background-color: #d4edda; color: #155724; }
    .estado-cancelado { background-color: #f8d7da; color: #721c24; }
    
    /* Modal */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); overflow-y: auto; animation: fadeIn 0.3s; }
    .modal-content { background-color: white; margin: 50px auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 700px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); animation: slideUp 0.3s; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef; }
    .modal-header h2 { margin: 0; color: #667eea; }
    .close { color: #aaa; font-size: 32px; font-weight: bold; cursor: pointer; transition: color 0.3s; line-height: 1; }
    .close:hover { color: #000; }
    
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Utilidades */
    .text-center { text-align: center; }
    .text-muted { color: #6c757d; }
    .mb-20 { margin-bottom: 20px; }
    .mt-20 { margin-top: 20px; }
    .no-resultados { padding: 60px 20px; text-align: center; color: #6c757d; font-size: 18px; }
    .no-resultados i { font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.5; }
</style>
