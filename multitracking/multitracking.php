<?php
 class multiTracking extends multiTracking_parent
 {
     /**
      * Returns DPD shipment tracking url if oxorder__oxtrackcode is supplied
      *
      * @return string
      */
     public function getShipmentTrackingUrl()
     {
         if ( $this->_sShipTrackUrl === null && $this->oxorder__oxtrackcode->value ) {
             $sTrackId = $this->oxorder__oxtrackcode->value;
             $sCarrier = $this->getShipmentTrackingCarrier();

             switch ($sCarrier) {
                 case "DHL":
                     $this->_sShipTrackUrl = "http://nolp.dhl.de/nextt-online-public/track.do?zip=".(empty($this->oxorder__oxdelzip->value)?$this->oxorder__oxbillzip->value:$this->oxorder__oxdelzip->value)."&idc=".$sTrackId."&lang=".($this->oxorder__oxlang->value==0?'de':'en');
                     break;
                case "DPAG":
                    $this->_sShipTrackUrl = "https://www.deutschepost.de/sendungsstatus/bzl/sendung/simpleQueryResult.html?local=".($this->oxorder__oxlang->value==0?'de':'en')."&form.sendungsnummer=".$sTrackId;
                    break;
                 case "HLG":
                     $this->_sShipTrackUrl = "http://tracking.hlg.de/Tracking.jsp?TrackID=".$sTrackId;
                     break;
                 case "DPD":
                     $this->_sShipTrackUrl = "http://extranet.dpd.de/cgi-bin/delistrack?typ=1&lang=de&pknr=".$sTrackId;
                     break;
                 case "GLS":
                     $this->_sShipTrackUrl = "https://gls-group.eu/DE/de/paketverfolgung?match=".$sTrackId;
                     break;
                 case "UPS":
                     $this->_sShipTrackUrl = "http://wwwapps.ups.com/WebTracking/processRequest?HTMLVersion=5.0&Requester=NES&AgreeToTermsAndConditions=yes&loc=de_DE&tracknum=".$sTrackId; 
                     break;
             
                 default:
                     $this->_sShipTrackUrl = parent::getShipmentTrackingUrl();
                     break;
             }
         }
        
         return $this->_sShipTrackUrl;
     }
 
      public function getShipmentTrackingCarrier()
     {
         $sTrackId = $this->oxorder__oxtrackcode->value;
         // DHL: http://pastebin.com/pFghdwYB
         if (
            preg_match("/^1Z\s?[0-9A-Z]{3}\s?[0-9A-Z]{3}\s?[0-9A-Z]{2}\s?[0-9A-Z]{4}\s?[0-9A-Z]{3}\s?[0-9A-Z]$/i", $sTrackId)) {
                $sCarrier = "UPS";
        } elseif(
            preg_match("/^\d{14}$/", $sTrackId)) {
                $sCarrier = "HLG";
        } elseif(
            preg_match("/^\d{11}$/", $sTrackId)) {
                $sCarrier = "GLS";
        } elseif(
            preg_match("/[A-Z]{3}\d{2}\.?\d{2}\.?(\d{3}\s?){3}/", $sTrackId) || 
            preg_match("/[A-Z]{3}\d{2}\.?\d{2}\.?\d{3}/", $sTrackId) || 
            preg_match("/(\d{12}|\d{16}|\d{20})/", $sTrackId)) {
                $sCarrier = "DHL";
         } elseif (
            preg_match("/RR\s?\d{4}\s?\d{5}\s?\d(?=DE)/", $sTrackId) || 
            preg_match("/NN\s?\d{2}\s?\d{3}\s?\d{3}\s?\d(?=DE(\s)?\d{3})/", $sTrackId) || 
            preg_match("/RA\d{9}(?=DE)/", $sTrackId) || preg_match("/LX\d{9}(?=DE)/", $sTrackId) ||
            preg_match("/LX\s?\d{4}\s?\d{4}\s?\d(?=DE)/", $sTrackId) || 
            preg_match("/LX\s?\d{4}\s?\d{4}\s?\d(?=DE)/", $sTrackId) || 
            preg_match("/XX\s?\d{2}\s?\d{3}\s?\d{3}\s?\d(?=DE)/", $sTrackId) || 
            preg_match("/RG\s?\d{2}\s?\d{3}\s?\d{3}\s?\d(?=DE)/", $sTrackId)) {
                $sCarrier = "DPAG";
         } else {
            $sCarrier = "NONE";
         }
        return $sCarrier;
    }
}