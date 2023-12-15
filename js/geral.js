
//scripts do header

function mostra_alertas(){
	md_alerta_bd = 'Recuperar alertas';
	
	$('#md_geral_tt').html('Alertas');
	$('#md_geral_bd').html(md_alerta_bd);
	$('#md_geral').modal('show');
}

function ConverteData(data){
	
	if ( !data || data.length <2 || data == "00/00/0000" || data == "0000-00-00" ) { 
		return ""; 
	} 

	time =  data.split(" ");
	if (time[1]) {
		tm = " "+time[1];
	} else {
		tm = "";
	}

	novadata = time[0];
	if (novadata.indexOf("/")!=-1) {//verifica se tem a barra /
		d =  novadata.split("/"); //tira a barra
		novadata = d[2]+"-"+d[1]+"-"+d[0]; //separa as datas $d[2] = ano $d[1] = mes etc...
		return novadata+tm;
	} else if (novadata.indexOf("-")!=-1) {//verifica se tem a barra /
		d =  novadata.split("-"); //tira a barra
		novadata = d[2]+"/"+d[1]+"/"+d[0]; //separa as datas $d[2] = ano $d[1] = mes etc...
		return novadata+tm;
	}  else {
		return "Data invalida";
	}
}

function hora_to_new_event(hora){
	hora=ConverteData(hora);
	if(hora.length < 7){ return null;}
	aux = hora.split(' ');
	return aux[0]+'T'+aux[1]+':00'; 
}

function maior_data(data1 , data2){
	//se a data1 for maior retorna 1 ==  se a data2 for maior retorna 2
	if(data1.length <8 || data2.length <8){return 0;}
	
	dt1 = dataInvertida(data1);
	dt2 = dataInvertida(data2);
	if(dt1==dt2){
		return 0;
	}
	else if( dt1>dt2 )
	{
		return 1;	
	}	
	else if( dt2>dt1)
	{
		return 2;	
	}
	else{
		return 3; //quando entra aqui?
	}
}

function dataInvertida(data){
	// se tiver / chama o converte data
	if (data.indexOf("/")!=-1) { data = ConverteData(data);}
	return  data.replace(/\D/g,'');
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function diffDays (data, data2=null) {

	var data1 = new Date(data);

	// Se não informado o segundo parametro assume a data atual 
	if(data2 == null) {
		var data2 = new Date();
	}

	return parseInt((data2 - data1) / (1000 * 60 * 60 * 24), 10);
}

