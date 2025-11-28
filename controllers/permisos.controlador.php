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

    static public function ctrObtenerNav($id){
        $data = PermisosModelo::mdlObtenerNav($id);
        return $data;
    }

    static public function ctrObtenerConfiguracion(){
        $data = PermisosModelo::mdlObtenerConfiguracion();
        return $data;
    }
}

?>