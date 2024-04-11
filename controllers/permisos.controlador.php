<?php 
class PermisosControlador {

    static public function ctrObtenerPermisos($id){
        $data = PermisosModelo::mdlObtenerPermisos($id);
        return $data;
    }
}?>