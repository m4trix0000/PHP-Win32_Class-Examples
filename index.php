<?php

#Win32_Class Windows
$WMI = new COM('winmgmts://');

//-> Arquitetura do sistema operacional
$SO_Architecture_Query = $WMI->ExecQuery("SELECT OSArchitecture, TotalVisibleMemorySize, Name, CSName FROM Win32_OperatingSystem");
foreach($SO_Architecture_Query as $value){

    $SO_Architecture = $value->OSArchitecture;
    $Memory = $value->TotalVisibleMemorySize;
    $SO_Name = explode("|", $value->Name);
    $PC_Name = $value->CSName;

}
echo $SO_Name[0].' / '.$SO_Architecture.'</br>';
echo $PC_Name.'</br>';
//<- Arquitetura do sistema operacional

//-> Processador 
$Processador_Query = $WMI->ExecQuery("SELECT Name FROM Win32_Processor");
foreach($Processador_Query as $value){
    $Processor = $value->Name;
}
echo $Processor.'</br>';
//<- Processador 

//-> Tipo da memória
$Memoria_Type_Query = $WMI->ExecQuery("SELECT SMBIOSMemoryType FROM Win32_PhysicalMemory");
foreach($Memoria_Type_Query as $value){
    $Memoria_Type = $value->SMBIOSMemoryType;
}

if(isset($Memoria_Type)){

    switch($Memoria_Type){

        case 2: $Memory_Type = "DRAM"; break;
        case 3: $Memory_Type = "Synchronous DRAM"; break;
        case 4: $Memory_Type = "Cache DRAM"; break;
        case 5: $Memory_Type = "EDO"; break;
        case 6: $Memory_Type = "EDRAM"; break;
        case 7: $Memory_Type = "VRAM"; break;
        case 8: $Memory_Type = "SRAM"; break;
        case 9: $Memory_Type = "RAM"; break;
        case 10: $Memory_Type = "ROM"; break;
        case 11: $Memory_Type = "Flash"; break;
        case 12: $Memory_Type = "EEPROM"; break;
        case 13: $Memory_Type = "FEPROM"; break;
        case 14: $Memory_Type = "EPROM"; break;
        case 15: $Memory_Type = "CDRAM"; break;
        case 16: $Memory_Type = "3DRAM"; break;
        case 17: $Memory_Type = "SDRAM"; break;
        case 18: $Memory_Type = "SGRAM"; break;
        case 19: $Memory_Type = "RDRAM"; break;
        case 20: $Memory_Type = "DDR"; break;
        case 21: $Memory_Type = "DDR2"; break;
        case 22: $Memory_Type = "DDR2 FB-DIMM"; break;
        case 23: $Memory_Type = "DDR2 FB-DIMM"; break;
        case 24: $Memory_Type = "DDR3"; break;
        case 25: $Memory_Type = "FBD2"; break;
        case 26: $Memory_Type = "DDR4"; break;
        //case 27: $Memory_Type = "DDR5"; break; Até o momento não encontrei referência a DDR5, para ver os modelos no futuro e comparar var_dump($Memory_Type);
        default: $Memory_Type = " "; break;

    }
}

if(isset($Memory_Type)){

    //Informações sobre a memória
    echo 'Memória:'.round(($Memory / 1024 / 1024)).'GB '.$Memory_Type.'</br>';

}else{
        
    //Informações sobre a memória
    echo 'Memória:'.round(($Memory / 1024 / 1024)).'GB</br>';
}
//<- Tipo da memória


//-> Rede Local
$Network_Configuration = WMI_Query_NetworkData("Win32_NetworkAdapterConfiguration","","IPEnabled = True"); 

for($i=0;$i<count($Network_Configuration['IPAddress']);$i++){ 
    
    echo ''.$Network_Configuration['IPAddress'][$i]."<br>";

}
//<- Rede Local

//Função para consulta de parametros de rede
function WMI_Query_NetworkData($WindowsClass, $Item, $Where){

    $wmiW = new COM('winmgmts://');        
    $QueryW = $wmiW->ExecQuery("SELECT * From $WindowsClass Where $Where");

    foreach ($QueryW as $wmi_call) {
    
        //MACAddress
        $MACAddress = $wmi_call->MACAddress;
        $Result['MACAddress'] = $MACAddress; 
        
        //Default Gateway
        $Gateway = $wmi_call->DefaultIPGateway;
        
        if(is_array($Gateway) || is_object($Gateway)){
        
            foreach($Gateway as $key => $value) { 

                if(strlen($value) < 16){

                    $Result['DefaultIPGateway'][] = $value;

                }
            }
        }
    
        //IP Address
        $ip = $wmi_call->IPAddress;
    
        foreach ($ip as $key => $value) {
    
            if(strlen($value) < 16){
                
                $Result['IPAddress'][] = $value;
            }
        }
    }

    return $Result;

}


?>