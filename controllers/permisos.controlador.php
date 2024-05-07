<?php 
class PermisosControlador {

    static public function ctrObtenerPermisos($id){
        $data = PermisosModelo::mdlObtenerPermisos($id);
        return $data;
    }

    static public function ctrObtenerPermisoSubmenu($id, $modulo){
        $data = PermisosModelo::mdlObtenerPermisoSubmenu($id, $modulo);
        return $data;
    }
}?>