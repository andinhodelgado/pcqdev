<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of EquipDAO
 *
 * @author anderson
 */
class EquipDAO extends Conn {
    //put your code here

    public function dados($base) {

        $select = "SELECT " 
                    . " EQUIP_ID AS \"idEquip\" "
                    . " , NRO_EQUIP AS \"nroEquip\" "
                    . " , TP_EQUIP AS \"tipoEquip\" "
                    . " FROM " 
                    . " USINAS.V_EQUIP_COMBAT_FOGO ";
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
    public function verifEquip($idCabec, $equip, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PCQ_EQUIP "
                . " WHERE "
                . " EQUIP_ID = " . $equip->idEquip
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $equip->dthrEquip . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " CABEC_ID = " . $idCabec;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    }

    public function insEquip($idCabec, $equip, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PCQ_EQUIP ("
                . " CABEC_ID "
                . " , EQUIP_ID "
                . " , TIPO "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idCabec
                . " , " . $equip->idEquip
                . " , " . $equip->tipoEquip
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($equip->dthrEquip, $base)
                . " , TO_DATE('" . $equip->dthrEquip . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
}
