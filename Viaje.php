<?php

class Viaje
{
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $idempresa; //OBJETO EMPRESA
    private $rnumeroempleado; // OBJETO RESPONSABLE
    private $vimporte;
    private $colPasajeros;
    private $mensaje;

    //constructor
    public function __construct()
    {
        $this->idviaje = 0;//modif
        $this->vdestino = '';
        $this->vcantmaxpasajeros = 0;
        $this->idempresa= null;
        $this->rnumeroempleado= null;
        $this->vimporte = 0;
        $this->colPasajeros = [];
        $this->mensaje = '';
        
    }

    public function cargar($idviaje,$vdestino, $vcantmaxpasajeros, $objempresa, $rnumeroempleado, $vimporte, $colPasajeros)
    {
        $this->setIdviaje($idviaje);
        $this->setVdestino($vdestino);
        $this->setVcantmaxpasajeros($vcantmaxpasajeros);
        $this->setObjempresa($objempresa);
        $this->setRnumeroempleado($rnumeroempleado);
        $this->setVimporte($vimporte);
        $this->setColPasajeros($colPasajeros); 
       
    }

    //metodos de acceso
    public function getIdviaje()
    {
        return $this->idviaje;
    }

    public function setIdviaje($idviaje)
    {
        $this->idviaje = $idviaje;
    }

    public function getVdestino()
    {
        return $this->vdestino;
    }

    public function setVdestino($vdestino)
    {
        $this->vdestino = $vdestino;
    }

    public function getVcantmaxpasajeros()
    {
        return $this->vcantmaxpasajeros;
    }

    public function setVcantmaxpasajeros($vcantmaxpasajeros)
    {
        $this->vcantmaxpasajeros = $vcantmaxpasajeros;
    }

    public function getObjempresa()
    {
        return $this->idempresa;
    }

    public function setObjempresa($idempresa)
    {
        $this->idempresa = $idempresa;
    }

    public function getRnumeroempleado()
    {
        return $this->rnumeroempleado;
    }

    public function setRnumeroempleado($rnumeroempleado)
    {
        $this->rnumeroempleado = $rnumeroempleado;
    }

    public function getVimporte()
    {
        return $this->vimporte;
    }

    public function setVimporte($vimporte)
    {
        $this->vimporte = $vimporte;
    }

    public function getColPasajeros() {
        return $this->colPasajeros;
    }

    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($nuevo)
    {
        $this->mensaje = $nuevo;
    }

    //toString
    public function __toString()
    {
        return "----------------------------------
            ID: " . $this->getIdviaje() .
            "\nDestino: " . $this->getVdestino() .
            "\nCantidad maxima de pasajeros: " . $this->getVcantMaxPasajeros() .
            "\nEmpresa: \n" . $this->getObjempresa() .
            "\nEmpleado Responsable: \n" . $this->getRnumeroempleado() .
            "\nImporte: $" . $this->getVimporte() ;
           
    }

    //funciones bd
    public function Buscar($id)
    {
        $base = new BaseDatos();
        $rta = false;
        $consulta = "SELECT * FROM Viaje WHERE idviaje=" . $id;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                   
                    $empresa = new Empresa();
                    $empresa->buscar($row2['idempresa']);
                    
                    $responsable = new Responsable();
                    $responsable->buscar($row2['rnumeroempleado']);
                    $this->cargar($id, $row2['vdestino'],$row2['vcantmaxpasajeros'], $empresa,$responsable, $row2['vimporte'],[]);
                    
                    $rta = true;
                }
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }

    public function listar($condicion = '')
    {
        $array = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM viaje";
        if ($condicion != '') {
            $consulta = $consulta . ' WHERE ' . $condicion;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $array = array();
                while ($row2 = $base->Registro()) {
                    
                    
                    $objViaje = new Viaje();
                    $objViaje->buscar($row2['idviaje']);
                    array_push($array, $objViaje);
                    //$array[] = $objViaje; modificacion
                }
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $array;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $rta = false;
        $empresa = $this->getObjempresa();
        $idEmpresa = $empresa->getIdEmpresa();
        $responsable = $this->getRnumeroempleado();
        $numResponsable = $responsable->getRnumeroempleado();
        $consulta = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) " .
        "VALUES ('{$this->getVdestino()}', {$this->getVcantmaxpasajeros()}, 
        {$idEmpresa}, {$numResponsable}, {$this->getVimporte()})";
        

        if($base->Iniciar()){

            if($id = $base->devuelveIDInsercion($consulta)){
                $this->setIdviaje($id);
                $exito = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $exito;
    }
        /*if ($base->Iniciar()) {
            $id = $base->devuelveIDInsercion($consulta);
            if($id !=null){
                $resp=  true;
                $this->setIdviaje($id);
            }
            //if ($base->Ejecutar($consulta)) {
              //  $rta = true;
             else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }*/
//ver para modificar
    public function modificar()
    {
        $rta = false;
        $base = new BaseDatos();
        $empresa = $this->getObjempresa();
        $idEmpresa = $empresa->getIdempresa();
        $responsable = $this->getRnumeroempleado();
        $numResponsable = $responsable->getRnumeroempleado();
        $consulta = "UPDATE viaje SET vdestino = '{$this->getVdestino()}', vcantmaxpasajeros = {$this->getVcantmaxpasajeros()},
         idempresa = {$idEmpresa}, rnumeroempleado = {$numResponsable}, vimporte = {$this->getVimporte()}  WHERE idviaje = {$this->getIdviaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $rta = true;
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }
//ver para modificar
    public function eliminar()
    {
        $base = new BaseDatos();
        $rta = false;
        $consulta = "DELETE FROM viaje WHERE idviaje = " . $this->getIdviaje();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $rta = true;
            } else {
                $this->setMensaje($base->getError());
            }
        } else {
            $this->setMensaje($base->getError());
        }
        return $rta;
    }
}
