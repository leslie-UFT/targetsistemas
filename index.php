<?php
    ini_set('display_errors',0);

    $GLOBALS['month'] = array(1 => 'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho', 'Agosto','Setembro','Outubro','Novembro','Dezembro');

    function soma(int $index,int $sum = 0, int $k = 0 ):int{
        while($k < $index)
        {
            $k++;
            $sum += $k;
        }
        return $sum;
    }

    echo "<br>**********************************************************************************************************************************************************************<br>";
    echo "Observe o trecho de código abaixo: int INDICE = 13, SOMA = 0, K = 0; <BR />";
    echo "**********************************************************************************************************************************************************************<br>";

    printf("<br />Resultado da Soma: %u <br/>",soma(13));

    function fibonacci(int $number, int $inverval = 15):string{
        $j = 1; 
        $i = 0;
        $t = Array('0'=>'0');

        for($k=0; $k <= $inverval;$k++){
            $o = $i + $j;
            $i = $j;
            $j = $o;
            $t[$o]=$o;
        }

        echo "<br/>Sequência Fibonacci: (".implode(", ",$t).")<br/>";

        if(isset($t[$number])){
            return " Número $number informado foi encontrado nas $inverval sequências geradas do Fibonacci!<br/>";
        }
        return " Número $number informado NÃO foi encontrado nas $inverval sequências geradas do Fibonacci!<br/>";
    }

    echo "<br>**********************************************************************************************************************************************************************<br>";
    echo "2) Dado a sequência de Fibonacci, onde se inicia por 0 e 1 e o próximo valor sempre será a soma dos 2 valores anteriores (exemplo: 0, 1, 1, 2, 3, 5, 8, 13, 21, 34...), escreva um programa na linguagem que desejar onde, informado um número, ele calcule a sequência de Fibonacci e retorne uma mensagem avisando se o número informado pertence ou não a sequência.<br/> ";
    echo "**********************************************************************************************************************************************************************<br>";

    echo "<br/>Resultado:".fibonacci(13)."<br />";

    function holiday(int $year){
        echo "<br>• Buscando feriados no ano: $year<br>";
        echo "<br>• OBS: Para funcionar essa busca é necessario TOKEN, da API https://api.invertexto.com/<br>";

        $resp = json_decode(file_get_contents("https://api.invertexto.com/v1/holidays/$year?token=2676|PSP7FqWXmEybG2uU84w4LKK0F4h80FRH&state=TO"));
        $feriado = array();

        if(!$resp){
            $resp = json_decode('[{"date":"2023-01-01","name":"Confraterniza\u00e7\u00e3o Universal","type":"feriado","level":"nacional"},{"date":"2023-02-20","name":"Carnaval","type":"facultativo","level":"nacional"},{"date":"2023-02-21","name":"Carnaval","type":"facultativo","level":"nacional"},{"date":"2023-02-22","name":"Quarta-feira de Cinzas","type":"facultativo","level":"nacional"},{"date":"2023-03-18","name":"Autonomia do Estado","type":"feriado","level":"estadual","law":"Lei estadual n\u00ba 960\/1998"},{"date":"2023-04-07","name":"Sexta-feira Santa","type":"facultativo","level":"nacional"},{"date":"2023-04-21","name":"Tiradentes","type":"feriado","level":"nacional"},{"date":"2023-05-01","name":"Dia do Trabalhador","type":"feriado","level":"nacional"},{"date":"2023-06-08","name":"Corpus Christi","type":"facultativo","level":"nacional"},{"date":"2023-09-07","name":"Independ\u00eancia do Brasil","type":"feriado","level":"nacional"},{"date":"2023-09-08","name":"Padroeira do Estado","type":"feriado","level":"estadual","law":"Lei estadual n\u00ba 627\/1993"},{"date":"2023-10-05","name":"Cria\u00e7\u00e3o do estado","type":"feriado","level":"estadual","law":"Lei estadual n\u00ba 98\/1989"},{"date":"2023-10-12","name":"Nossa Senhora Aparecida","type":"feriado","level":"nacional"},{"date":"2023-10-28","name":"Dia do Servidor P\u00fablico","type":"facultativo","level":"nacional"},{"date":"2023-11-02","name":"Finados","type":"feriado","level":"nacional"},{"date":"2023-11-15","name":"Proclama\u00e7\u00e3o da Rep\u00fablica","type":"feriado","level":"nacional"},{"date":"2023-12-24","name":"V\u00e9spera de Natal","type":"facultativo","level":"nacional"},{"date":"2023-12-25","name":"Natal","type":"feriado","level":"nacional"},{"date":"2023-12-31","name":"V\u00e9spera de Ano-Novo","type":"facultativo","level":"nacional"}]');
        }

        foreach($resp as $k){
            $feriado[$year][substr($k->date,5,2)][substr($k->date,8,2)] = $k;
        }
        return $feriado;
    }    

    function generate_cal(int $year , int $month ):array{
        echo "<br>• Gerando calendário mensal de ".$GLOBALS['month'][(int)$month]."/$year<br>";
        $announcing = array();
        $holiday = holiday($year);
        
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);

        for($i=1;$i <= cal_days_in_month(CAL_GREGORIAN, $month, $year);$i++){
            $day = str_pad($i, 2, '0', STR_PAD_LEFT);


            if(isset($holiday[$year][$month][$day])){
                $announcing["$year"]["$month"]["$day"] = array('date'=> "$day/$month/$year",'week'=> date('w',strtotime("$day-$month-$year")),"holidays" => $holiday[$year][$month][$day]);
            }
            else{
                $announcing["$year"]["$month"]["$day"] = array('date'=> "$day/$month/$year",'week'=> date('w',strtotime("$day-$month-$year")));
            }
        }

        echo "<pre>";
        print_r($announcing);
        echo "</pre>";

        return $announcing;
    }

    function announcing(array $announcing){
        

        foreach($announcing as $years => $_year){
            foreach($_year as $months => $_month){
                echo "<br>• Gerando caixa do ".$GLOBALS['month'][(int)$months]."/$years<br>";
                foreach($_month as $days => $_days){
                    //echo "$years $months  $days"; exit;
                    $box = mt_rand(0,1);

                    for($i=0;$i <= $box;$i++){                        
                        $value =  mt_rand(100,999);
                        $decimal =  mt_rand(00,99);

                        if(!in_array($_days['week'],array(0,1)) || !isset($_days['holidays'])){
                            $data["cost"] = "$value.$decimal";
                            $announcing[$years][$months][$days] =  array_merge($announcing[$years][$months][$days],$data);
                        }
                    }
                }
            }
        }

        echo "<br>• Persistindo faturamento do mês: ".$GLOBALS['month'][(int)$months]."/$years, no arquivo announcing.json<br>";

        $myfile = fopen("announcing.json", "w");
        fwrite($myfile, json_encode($announcing));
        fclose($myfile);
    }

    function resume(? int $year = 2023, ? int $month = 2){
        
        announcing(generate_cal($year, $month));

        $myfile = fopen("announcing.json", "r") or die("Unable to open file!");
        $file = fread($myfile,filesize("announcing.json"));
        $arr = array_values(json_decode($file,true));

        echo "<br>• Buscando faturamento do mês: ".$GLOBALS['month'][(int)$month]."/$year, do arquivo announcing.json<br>";
        echo "------------------------------------------------------------------------------------------------------------<br>";
        

        array_walk_recursive($arr, function($value, $key) use (&$cost) {
            if($key === "cost"){
                $cost[] = $value;
            }
        }, $cost);

        echo "Valores de caixa <br>";
        echo implode(", ",$cost);
        echo "------------------------------------------------------------------------------------------------------------<br>";

        $sum = array_sum($cost);
        $avg = bcdiv($sum,count($cost),2);

        array_walk_recursive($cost, function($value, $key) use (&$avg,&$cost) {
            //echo "bccomp($avg,$value)  ==>> ". bccomp($value,$avg,2); exit;
            if(bccomp($value,$avg,2) !== 1){
                unset($cost[$key]);
            }
        }, $avg);

        echo "Valores de diários abaixo da MÉDIA mensal<br>";
        echo implode(", ",$cost);
        echo "------------------------------------------------------------------------------------------------------------<br>";


        echo "------------------------------------------------------------------------------------------------------------<br>";
        echo "Faturamento ".$GLOBALS['month'][(int)$month]."/$year<br>";
        echo "------------------------------------------------------------------------------------------------------------<br>";
        echo "• O MENOR valor R$ ".number_format(min($cost),2,",",".")." de faturamento ocorrido em um dia do mês<br/>";
        echo "• O MAIOR valor R$ ".number_format(max($cost),2,",",".")." de faturamento ocorrido em um dia do mês<br/>";
        echo "• O TOTAL valor R$ ".number_format($sum,2,",",".")." de faturamento mês<br/>";
        echo "• O MÉDIA valor R$ ".number_format($avg,2,",",".")." de faturamento mês<br/>";
        echo "• Número de dias ".count($cost)." no mês em que o valor de faturamento diário foi superior à média mensal<br/>";
    
    
    }

    echo "<br>**********************************************************************************************************************************************************************<br>";
    echo "3) Dado um vetor que guarda o valor de faturamento diário de uma distribuidora, faça um programa, na linguagem que desejar, que calcule e retorne:<BR />";
    echo "**********************************************************************************************************************************************************************<br>";
    
    resume(2023, 2);
  
    echo "<br>**********************************************************************************************************************************************************************<br>";
    echo "4) Dado o valor de faturamento mensal de uma distribuidora, detalhado por estado:<br>";
    echo "**********************************************************************************************************************************************************************<br>";

    $announcing_month = array('SP'=> "R$ 67.836,43",'RJ' => "R$ 36.678,66", 'MG' => "R$ 29.229,88", 'ES' => "R$ 27.165,48", 'Outros' =>"R$ 19.849,53");

    $cost_month = array();
    array_walk_recursive($announcing_month, function($value, $key) use (&$cost_month) {
        $cost_month[$key] = str_replace(",",".",preg_replace("/[^0-9,]/", "",$value));
    }, $cost_month);

    $totals = array_sum($cost_month);

    array_walk_recursive($cost_month, function($value, $key) use (&$announcing_month,$totals) {
        $announcing_month[$key] = array("Valor"=> "R$ ".number_format($value,2,",","."),"Percentual"=> bcmul(bcdiv($value,$totals,8),100,4)." %");
    }, $announcing_month);


    echo "<pre>";
    print_r($announcing_month);
    echo "<br />• Valor Total R$ ".number_format($totals,2,",",".")."<br/>";
    echo "------------------------------------------------------------------------------------------------------------<br>";

    echo "<br>**********************************************************************************************************************************************************************<br>";
    echo "5) Escreva um programa que inverta os caracteres de um string<br>";
    echo "**********************************************************************************************************************************************************************<br>";

    echo $phrase  = "A marca da cultura de consumo é a redução do SER para TER.";
 
    $p = preg_split('//u', $phrase);

    for($i = count($p); $i > 0 ; $i--){
        $phrase_new[] = $p[$i];
    }

    echo "<br>FRASE: ".implode($phrase_new);
