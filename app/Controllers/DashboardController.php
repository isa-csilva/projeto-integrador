<?php

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        if ($usuario !== null) {
            $usuario['perfil_label'] = Auth::profileLabel($usuario['perfil']);
        }

        $this->view('dashboard.index', array(
            'title' => 'Dashboard',
            'usuario' => $usuario,
            'authFlash' => Auth::pullFlash(),
            'canManageStudents' => Auth::hasAnyProfile(array(
                Usuario::PERFIL_ADMINISTRADOR,
                Usuario::PERFIL_SECRETARIA
            )),
            'canManageUsers' => Auth::hasAnyProfile(Usuario::PERFIL_ADMINISTRADOR)
        ));
    }
}
