function ue_cargarmunicipios()
{
	f=document.form1;
	f.cmbcodmun.length=0;
	f.cmbcodmun.options[0]= new Option('--Seleccione--','');
	f.cmbcodloc.length=0;
	f.cmbcodloc.options[0]= new Option('--Seleccione--','');
	if(f.cmbcodent.value=="01")// DTTO FEDERAL
	{
		f.cmbcodmun.options[1]= new Option('LIBERTADOR','001');
	}
	if(f.cmbcodent.value=="02")// AMAZONAS
	{
		f.cmbcodmun.options[1]= new Option('ALTO ORINOCO','001');
		f.cmbcodmun.options[2]= new Option('ATABAPO','002');
		f.cmbcodmun.options[3]= new Option('ATURES','003');
		f.cmbcodmun.options[4]= new Option('AUTANA','004');
		f.cmbcodmun.options[5]= new Option('MAROA','005');
		f.cmbcodmun.options[6]= new Option('MANAPIARE','006');
		f.cmbcodmun.options[7]= new Option('RÍO NEGRO','007');
	}
	if(f.cmbcodent.value=="03")// ANZOATEGUI
	{
		f.cmbcodmun.options[1]= new Option('ANACO','001');
		f.cmbcodmun.options[2]= new Option('ARAGUA','002');
		f.cmbcodmun.options[3]= new Option('FERNANDO DE PEÑALVER','003');
		f.cmbcodmun.options[4]= new Option('FRANCISCO DEL CARMEN CARVAJAL','004');
		f.cmbcodmun.options[5]= new Option('FRANCISCO DE MIRANDA','005');
		f.cmbcodmun.options[6]= new Option('GUANTA','006');
		f.cmbcodmun.options[7]= new Option('INDEPENDENCIA','007');
		f.cmbcodmun.options[8]= new Option('JUAN ANTONIO SOTILLO','008');
		f.cmbcodmun.options[9]= new Option('JUAN MANUEL CAJIGAL','009');
		f.cmbcodmun.options[10]= new Option('JOSE GREGORIO MONAGAS','010');
		f.cmbcodmun.options[11]= new Option('LIBERTAD','011');
		f.cmbcodmun.options[12]= new Option('MANUEL EZEQUIEL BRUZUAL','012');
		f.cmbcodmun.options[13]= new Option('PEDRO MARIA FREITES','013');
		f.cmbcodmun.options[14]= new Option('PÍRITU','014');
		f.cmbcodmun.options[15]= new Option('SAN JOSE DE GUANIPA','015');
		f.cmbcodmun.options[16]= new Option('SAN JUAN DE CAPISTRANO','016');
		f.cmbcodmun.options[17]= new Option('SANTA ANA','017');
		f.cmbcodmun.options[18]= new Option('SIMON BOLIVAR','018');
		f.cmbcodmun.options[19]= new Option('SIMON RODRIGUEZ','019');
		f.cmbcodmun.options[20]= new Option('SIR ARTUR MC GREGOR','020');
		f.cmbcodmun.options[21]= new Option('TURISTICO DIEGO BAUTISTA URBANEJA','021');
	}
	if(f.cmbcodent.value=="04")// APURE
	{
		f.cmbcodmun.options[1]= new Option('ACHAGUAS','001');
		f.cmbcodmun.options[2]= new Option('BIRUACA','002');
		f.cmbcodmun.options[3]= new Option('MUÑOZ','003');
		f.cmbcodmun.options[4]= new Option('PÁEZ','004');
		f.cmbcodmun.options[5]= new Option('PEDRO CAMEJO','005');
		f.cmbcodmun.options[6]= new Option('RÓMULO GALLEGOS','006');
		f.cmbcodmun.options[7]= new Option('SAN FERNANDO','007');
	}
	if(f.cmbcodent.value=="05")// ARAGUA
	{
		f.cmbcodmun.options[1]= new Option('BOLÍVAR','001');
		f.cmbcodmun.options[2]= new Option('CAMATAGUA','002');
		f.cmbcodmun.options[3]= new Option('GIRARDOT','003');
		f.cmbcodmun.options[4]= new Option('JOSÉ ÁNGEL LAMAS','004');
		f.cmbcodmun.options[5]= new Option('JOSÉ FÉLIX RIBAS','005');
		f.cmbcodmun.options[6]= new Option('JOSÉ RAFAEL REVENGA','006');
		f.cmbcodmun.options[7]= new Option('LIBERTADOR','007');
		f.cmbcodmun.options[8]= new Option('MARIO BRICEÑO IRAGORRY','008');
		f.cmbcodmun.options[9]= new Option('SAN CASIMIRO','009');
		f.cmbcodmun.options[10]= new Option('SAN SEBASTIÁN','010');
		f.cmbcodmun.options[11]= new Option('SANTIAGO MARIÑO','011');
		f.cmbcodmun.options[12]= new Option('SANTOS MICHELENA','012');
		f.cmbcodmun.options[13]= new Option('SUCRE','013');
		f.cmbcodmun.options[14]= new Option('TOVAR','014');
		f.cmbcodmun.options[15]= new Option('URDANETA','015');
		f.cmbcodmun.options[16]= new Option('ZAMORA','016');
		f.cmbcodmun.options[17]= new Option('FRANCISCO LINARES ALCÁNTARA','017');
		f.cmbcodmun.options[18]= new Option('OCUMARE DE LA COSTA DE ORO','018');
	}
	if(f.cmbcodent.value=="06")// BARINAS
	{
		f.cmbcodmun.options[1]= new Option('ALBERTO ARVELO TORREALBA','001');
		f.cmbcodmun.options[2]= new Option('ANTONIO JOSÉ DE SUCRE','002');
		f.cmbcodmun.options[3]= new Option('ARISMENDI','003');
		f.cmbcodmun.options[4]= new Option('BARINAS','004');
		f.cmbcodmun.options[5]= new Option('BOLÍVAR','005');
		f.cmbcodmun.options[6]= new Option('CRUZ PAREDES','006');
		f.cmbcodmun.options[7]= new Option('EZEQUIEL ZAMORA','007');
		f.cmbcodmun.options[8]= new Option('OBISPOS','008');
		f.cmbcodmun.options[9]= new Option('PEDRAZA','009');
		f.cmbcodmun.options[10]= new Option('ROJAS','010');
		f.cmbcodmun.options[11]= new Option('SOSA','011');
		f.cmbcodmun.options[12]= new Option('ANDRÉS ELOY BLANCO','012');
	}
	if(f.cmbcodent.value=="07")// BOLIVAR
	{
		f.cmbcodmun.options[1]= new Option('CARONÍ','001');
		f.cmbcodmun.options[2]= new Option('CEDEÑO','002');
		f.cmbcodmun.options[3]= new Option('EL CALLAO','003');
		f.cmbcodmun.options[4]= new Option('GRAN SABANA','004');
		f.cmbcodmun.options[5]= new Option('HERES','005');
		f.cmbcodmun.options[6]= new Option('PIAR','006');
		f.cmbcodmun.options[7]= new Option('RAÚL LEONI','007');
		f.cmbcodmun.options[8]= new Option('ROSCIO','008');
		f.cmbcodmun.options[9]= new Option('SIFONTES','009');
		f.cmbcodmun.options[10]= new Option('SUCRE','010');
		f.cmbcodmun.options[11]= new Option('PADRE PEDRO CHIEN','011');
	}
	if(f.cmbcodent.value=="08")// CARABOBO
	{
		f.cmbcodmun.options[1]= new Option('BEJUMA','001');
		f.cmbcodmun.options[2]= new Option('CARLOS ARVELO','002');
		f.cmbcodmun.options[3]= new Option('DIEGO IBARRA','003');
		f.cmbcodmun.options[4]= new Option('GUACARA','004');
		f.cmbcodmun.options[5]= new Option('JUAN JOSÉ MORA','005');
		f.cmbcodmun.options[6]= new Option('LIBERTADOR','006');
		f.cmbcodmun.options[7]= new Option('LOS GUAYOS','007');
		f.cmbcodmun.options[8]= new Option('MIRANDA','008');
		f.cmbcodmun.options[9]= new Option('MONTALBÁN','009');
		f.cmbcodmun.options[10]= new Option('NAGUANAGUA','010');
		f.cmbcodmun.options[11]= new Option('PUERTO CABELLO','011');
		f.cmbcodmun.options[12]= new Option('SAN DIEGO','012');
		f.cmbcodmun.options[13]= new Option('SAN JOAQUÍN','013');
		f.cmbcodmun.options[14]= new Option('VALENCIA','014');
	}
	if(f.cmbcodent.value=="09")// COJEDES
	{
		f.cmbcodmun.options[1]= new Option('ANZOÁTEGUI','001');
		f.cmbcodmun.options[2]= new Option('FALCÓN','002');
		f.cmbcodmun.options[3]= new Option('GIRARDOT','003');
		f.cmbcodmun.options[4]= new Option('LIMA BLANCO','004');
		f.cmbcodmun.options[5]= new Option('EL PAO DE SAN JUAN BAUTISTA','005');
		f.cmbcodmun.options[6]= new Option('RICAURTE','006');
		f.cmbcodmun.options[7]= new Option('RÓMULO GALLEGOS','007');
		f.cmbcodmun.options[8]= new Option('SAN CARLOS DE AUSTRIA','008');
		f.cmbcodmun.options[9]= new Option('TINACO','009');
}
	if(f.cmbcodent.value=="10")// DELTA AMACURO
	{
		f.cmbcodmun.options[1]= new Option('ANTONIO DÍAZ','001');
		f.cmbcodmun.options[2]= new Option('CASACOIMA','002');
		f.cmbcodmun.options[3]= new Option('PEDERNALES','003');
		f.cmbcodmun.options[4]= new Option('TUCUPITA','004');
	}
	if(f.cmbcodent.value=="11")// FALCON
	{
		f.cmbcodmun.options[1]= new Option('ACOSTA','001');
		f.cmbcodmun.options[2]= new Option('BOLÍVAR','002');
		f.cmbcodmun.options[3]= new Option('BUCHIVACOA','003');
		f.cmbcodmun.options[4]= new Option('CACIQUE MANAURE','004');
		f.cmbcodmun.options[5]= new Option('CARIRUBANA','005');
		f.cmbcodmun.options[6]= new Option('COLINA','006');
		f.cmbcodmun.options[7]= new Option('DABAJURO','007');
		f.cmbcodmun.options[8]= new Option('DEMOCRACIA','008');
		f.cmbcodmun.options[9]= new Option('FALCÓN','009');
		f.cmbcodmun.options[10]= new Option('FEDERACIÓN','010');
		f.cmbcodmun.options[11]= new Option('JACURA','011');
		f.cmbcodmun.options[12]= new Option('LOS TAQUES','012');
		f.cmbcodmun.options[13]= new Option('MAUROA','013');
		f.cmbcodmun.options[14]= new Option('MIRANDA','014');
		f.cmbcodmun.options[15]= new Option('MONSEÑOR ITURRIZA','015');
		f.cmbcodmun.options[16]= new Option('PALMASOLA','016');
		f.cmbcodmun.options[17]= new Option('PETIT','017');
		f.cmbcodmun.options[18]= new Option('PÍRITU','018');
		f.cmbcodmun.options[19]= new Option('SAN FRANCISCO','019');
		f.cmbcodmun.options[20]= new Option('SILVA','020');
		f.cmbcodmun.options[21]= new Option('SUCRE','021');
		f.cmbcodmun.options[22]= new Option('TOCOPERO','022');
		f.cmbcodmun.options[23]= new Option('UNIÓN','023');
		f.cmbcodmun.options[24]= new Option('URUMACO','024');
		f.cmbcodmun.options[25]= new Option('ZAMORA','025');
	}
	if(f.cmbcodent.value=="12")// GUARICO
	{
		f.cmbcodmun.options[1]= new Option('CAMAGUAN','001');
		f.cmbcodmun.options[2]= new Option('CHAGUARAMAS','002');
		f.cmbcodmun.options[3]= new Option('EL SOCORRO','003');
		f.cmbcodmun.options[4]= new Option('SAN GERONIMO DE GUAYABAL','004');
		f.cmbcodmun.options[5]= new Option('LEONARDO INFANTE','005');
		f.cmbcodmun.options[6]= new Option('LAS MERCEDES','006');
		f.cmbcodmun.options[7]= new Option('JULIAN MECHADO','007');
		f.cmbcodmun.options[8]= new Option('FRANCISCO DE MIRANDA','008');
		f.cmbcodmun.options[9]= new Option('JOSE TADEO MONAGAS','009');
		f.cmbcodmun.options[10]= new Option('ORTIZ','010');
		f.cmbcodmun.options[11]= new Option('JOSE FELIX RIBAS','011');
		f.cmbcodmun.options[12]= new Option('JUAN GERMAN ROSCIO','012');
		f.cmbcodmun.options[13]= new Option('SAN JOSE DE GUARIBE','013');
		f.cmbcodmun.options[14]= new Option('SANTA MARIA DE IPIRE','014');
		f.cmbcodmun.options[15]= new Option('PEDRO ZARAZA','015');
	}
	if(f.cmbcodent.value=="13")// LARA
	{
		f.cmbcodmun.options[1]= new Option('ANDRÉS ELOY BLANCO','001');
		f.cmbcodmun.options[2]= new Option('CRESPO','002');
		f.cmbcodmun.options[3]= new Option('IRIBARREN','003');
		f.cmbcodmun.options[4]= new Option('JIMÉNEZ','004');
		f.cmbcodmun.options[5]= new Option('MORÁN','005');
		f.cmbcodmun.options[6]= new Option('PALAVECINO','006');
		f.cmbcodmun.options[7]= new Option('SIMÓN PLANAS','007');
		f.cmbcodmun.options[8]= new Option('TORRES','008');
		f.cmbcodmun.options[9]= new Option('URDANETA','009');
	}
	if(f.cmbcodent.value=="14")// MERIDA
	{
		f.cmbcodmun.options[1]= new Option('ALBERTO ADRIANI','001');
		f.cmbcodmun.options[2]= new Option('ANDRÉS BELLO','002');
		f.cmbcodmun.options[3]= new Option('ANTONIO PINTO SALINAS','003');
		f.cmbcodmun.options[4]= new Option('ARICAGUA','004');
		f.cmbcodmun.options[5]= new Option('ARZOBISPO CHACÓN','005');
		f.cmbcodmun.options[6]= new Option('CAMPO ELÍAS','006');
		f.cmbcodmun.options[7]= new Option('CARACCIOLO PARRA OLMEDO','007');
		f.cmbcodmun.options[8]= new Option('CARDENAL QUINTERO','008');
		f.cmbcodmun.options[9]= new Option('GUARAQUE','009');
		f.cmbcodmun.options[10]= new Option('JULIO CÉSAR SALAS','010');
		f.cmbcodmun.options[11]= new Option('JUSTO BRICEÑO','011');
		f.cmbcodmun.options[12]= new Option('LIBERTADOR','012');
		f.cmbcodmun.options[13]= new Option('MIRANDA','013');
		f.cmbcodmun.options[14]= new Option('OBISPO RAMOS DE LORA','014');
		f.cmbcodmun.options[15]= new Option('PADRE NOGUERA','015');
		f.cmbcodmun.options[16]= new Option('PUEBLO LLANO','016');
		f.cmbcodmun.options[17]= new Option('RANGEL','017');
		f.cmbcodmun.options[18]= new Option('RIVAS DÁVILA','018');
		f.cmbcodmun.options[19]= new Option('SANTOS MARQUINA','019');
		f.cmbcodmun.options[20]= new Option('SUCRE','020');
		f.cmbcodmun.options[21]= new Option('TOVAR','021');
		f.cmbcodmun.options[22]= new Option('TULIO FEBRES CORDERO','022');
		f.cmbcodmun.options[23]= new Option('ZEA','023');
	}
	if(f.cmbcodent.value=="15")// MIRANDA
	{
		f.cmbcodmun.options[1]= new Option('ACEVEDO','001');
		f.cmbcodmun.options[2]= new Option('ANDRÉS BELLO','002');
		f.cmbcodmun.options[3]= new Option('BARUTA','003');
		f.cmbcodmun.options[4]= new Option('BRIÓN','004');
		f.cmbcodmun.options[5]= new Option('BUROZ','005');
		f.cmbcodmun.options[6]= new Option('CARRIZAL','006');
		f.cmbcodmun.options[7]= new Option('CHACAO','007');
		f.cmbcodmun.options[8]= new Option('CRISTÓBAL ROJAS','008');
		f.cmbcodmun.options[9]= new Option('EL HATILLO','009');
		f.cmbcodmun.options[10]= new Option('GUAICAIPURO','010');
		f.cmbcodmun.options[11]= new Option('INDEPENDENCIA','011');
		f.cmbcodmun.options[12]= new Option('LANDER','012');
		f.cmbcodmun.options[13]= new Option('LOS SALIAS','013');
		f.cmbcodmun.options[14]= new Option('PÁEZ','014');
		f.cmbcodmun.options[15]= new Option('PAZ CASTILLO','015');
		f.cmbcodmun.options[16]= new Option('PEDRO GUAL','016');
		f.cmbcodmun.options[17]= new Option('PLAZA','017');
		f.cmbcodmun.options[18]= new Option('SIMÓN BOLÍVAR','018');
		f.cmbcodmun.options[19]= new Option('SUCRE','019');
		f.cmbcodmun.options[20]= new Option('URDANETA','020');
		f.cmbcodmun.options[21]= new Option('ZAMORA','021');
	}
	if(f.cmbcodent.value=="16")// MONAGAS
	{
		f.cmbcodmun.options[1]= new Option('ACOSTA','001');
		f.cmbcodmun.options[2]= new Option('AGUASAY','002');
		f.cmbcodmun.options[3]= new Option('BOLÍVAR','003');
		f.cmbcodmun.options[4]= new Option('CARIPE','004');
		f.cmbcodmun.options[5]= new Option('CEDEÑO','005');
		f.cmbcodmun.options[6]= new Option('EZEQUIEL ZAMORA','006');
		f.cmbcodmun.options[7]= new Option('LIBERTADOR','007');
		f.cmbcodmun.options[8]= new Option('MATURIN','008');
		f.cmbcodmun.options[9]= new Option('PIAR','009');
		f.cmbcodmun.options[10]= new Option('PUNCERES','010');
		f.cmbcodmun.options[11]= new Option('SANTA BÁRBARA','011');
		f.cmbcodmun.options[12]= new Option('SOTILLO','012');
		f.cmbcodmun.options[13]= new Option('URACOA','013');
	}
	if(f.cmbcodent.value=="17")// NUEVA ESPARTA
	{
		f.cmbcodmun.options[1]= new Option('ANTOLÍN DEL CAMPO','001');
		f.cmbcodmun.options[2]= new Option('ARISMENDI','002');
		f.cmbcodmun.options[3]= new Option('DÍAZ','003');
		f.cmbcodmun.options[4]= new Option('GARCÍA','004');
		f.cmbcodmun.options[5]= new Option('GÓMEZ','005');
		f.cmbcodmun.options[6]= new Option('MANEIRO','006');
		f.cmbcodmun.options[7]= new Option('MARCANO','007');
		f.cmbcodmun.options[8]= new Option('MARIÑO','008');
		f.cmbcodmun.options[9]= new Option('PENÍNSULA DE MACANAO','009');
		f.cmbcodmun.options[10]= new Option('TUBORES','010');
		f.cmbcodmun.options[11]= new Option('VILLALBA','011');
	}
	if(f.cmbcodent.value=="18")// PORTUGUESA
	{
		f.cmbcodmun.options[1]= new Option('AGUA BLANCA','001');
		f.cmbcodmun.options[2]= new Option('ARAURE','002');
		f.cmbcodmun.options[3]= new Option('ESTELLER','003');
		f.cmbcodmun.options[4]= new Option('GUANARE','004');
		f.cmbcodmun.options[5]= new Option('GUANARITO','005');
		f.cmbcodmun.options[6]= new Option('MONSEÑOR JOSÉ VICENTE DE UNDA','006');
		f.cmbcodmun.options[7]= new Option('OSPINO','007');
		f.cmbcodmun.options[8]= new Option('PÁEZ','008');
		f.cmbcodmun.options[9]= new Option('PAPELÓN','009');
		f.cmbcodmun.options[10]= new Option('SAN GENARO DE BOCONOITO','010');
		f.cmbcodmun.options[11]= new Option('SAN RAFAEL DE ONOTO','011');
		f.cmbcodmun.options[12]= new Option('SANTA ROSALÍA','012');
		f.cmbcodmun.options[13]= new Option('SUCRE','013');
		f.cmbcodmun.options[14]= new Option('TURÉN','014');
	}
	if(f.cmbcodent.value=="19")// SUCRE
	{
		f.cmbcodmun.options[1]= new Option('ANDRÉS ELOY BLANCO','001');
		f.cmbcodmun.options[2]= new Option('ANDRÉS MATA','002');
		f.cmbcodmun.options[3]= new Option('ARISMENDI','003');
		f.cmbcodmun.options[4]= new Option('BENÍTEZ','004');
		f.cmbcodmun.options[5]= new Option('BERMÚDEZ','005');
		f.cmbcodmun.options[6]= new Option('BOLÍVAR','006');
		f.cmbcodmun.options[7]= new Option('CAJIGAL','007');
		f.cmbcodmun.options[8]= new Option('CRUZ SALMERÓN ACOSTA','008');
		f.cmbcodmun.options[9]= new Option('LIBERTADOR','009');
		f.cmbcodmun.options[10]= new Option('MARIÑO','010');
		f.cmbcodmun.options[11]= new Option('MEJÍA','011');
		f.cmbcodmun.options[12]= new Option('MONTES','012');
		f.cmbcodmun.options[13]= new Option('RIBERO','013');
		f.cmbcodmun.options[14]= new Option('SUCRE','014');
		f.cmbcodmun.options[15]= new Option('VALDEZ','015');
	}
	if(f.cmbcodent.value=="20")// TACHIRA
	{
		f.cmbcodmun.options[1]= new Option('ANDRÉS BELLO','001');
		f.cmbcodmun.options[2]= new Option('ANTONIO RÓMULO COSTA','002');
		f.cmbcodmun.options[3]= new Option('AYACUCHO','003');
		f.cmbcodmun.options[4]= new Option('BOLÍVAR','004');
		f.cmbcodmun.options[5]= new Option('CÁRDENAS','005');
		f.cmbcodmun.options[6]= new Option('CÓRDOBA','006');
		f.cmbcodmun.options[7]= new Option('FERNÁNDEZ FEO','007');
		f.cmbcodmun.options[8]= new Option('FRANCISCO DE MIRANDA','008');
		f.cmbcodmun.options[9]= new Option('GARCÍA DE HEVIA','009');
		f.cmbcodmun.options[10]= new Option('GUÁSIMOS','010');
		f.cmbcodmun.options[11]= new Option('INDEPENDENCIA','011');
		f.cmbcodmun.options[12]= new Option('JÁUREGUI','012');
		f.cmbcodmun.options[13]= new Option('JOSÉ MARÍA VARGAS','013');
		f.cmbcodmun.options[14]= new Option('JUNÍN','014');
		f.cmbcodmun.options[15]= new Option('LIBERTAD','015');
		f.cmbcodmun.options[16]= new Option('LIBERTADOR','016');
		f.cmbcodmun.options[17]= new Option('LOBATERA','017');
		f.cmbcodmun.options[18]= new Option('MICHELENA','018');
		f.cmbcodmun.options[19]= new Option('PANAMERICANO','019');
		f.cmbcodmun.options[20]= new Option('PEDRO MARÍA UREÑA','020');
		f.cmbcodmun.options[21]= new Option('RAFAEL URDANETA','021');
		f.cmbcodmun.options[22]= new Option('SAMUEL DARÍO MALDONADO','022');
		f.cmbcodmun.options[23]= new Option('SAN CRISTÓBAL','023');
		f.cmbcodmun.options[24]= new Option('SEBORUCO','024');
		f.cmbcodmun.options[25]= new Option('SIMÓN RODRÍGUEZ','025');
		f.cmbcodmun.options[26]= new Option('SUCRE','026');
		f.cmbcodmun.options[27]= new Option('TORBES','027');
		f.cmbcodmun.options[28]= new Option('URIBANTE','028');
		f.cmbcodmun.options[29]= new Option('SAN JUDAS TADEO','029');
	}
	if(f.cmbcodent.value=="21")// TRUJILLO
	{
		f.cmbcodmun.options[1]= new Option('ANDRÉS BELLO','001');
		f.cmbcodmun.options[2]= new Option('BOCONÓ','002');
		f.cmbcodmun.options[3]= new Option('BOLÍVAR','003');
		f.cmbcodmun.options[4]= new Option('CANDELARIA','004');
		f.cmbcodmun.options[5]= new Option('CARACHE','005');
		f.cmbcodmun.options[6]= new Option('ESCUQUE','006');
		f.cmbcodmun.options[7]= new Option('JOSÉ FELIPE MÁRQUEZ CAÑIZALES','007');
		f.cmbcodmun.options[8]= new Option('JOSÉ VICENTE CAMPO ELÍAS','008');
		f.cmbcodmun.options[9]= new Option('LA CEIBA','009');
		f.cmbcodmun.options[10]= new Option('MIRANDA','010');
		f.cmbcodmun.options[11]= new Option('MONTE CARMELO','011');
		f.cmbcodmun.options[12]= new Option('MOTATÁN','012');
		f.cmbcodmun.options[13]= new Option('PAMPÁN','013');
		f.cmbcodmun.options[14]= new Option('PAMPANITO','014');
		f.cmbcodmun.options[15]= new Option('RAFAEL RANGEL','015');
		f.cmbcodmun.options[16]= new Option('SAN RAFAEL DE CARVAJAL','016');
		f.cmbcodmun.options[17]= new Option('SUCRE','017');
		f.cmbcodmun.options[18]= new Option('TRUJILLO','018');
		f.cmbcodmun.options[19]= new Option('URDANETA','019');
		f.cmbcodmun.options[20]= new Option('VALERA','020');
	}
	if(f.cmbcodent.value=="22")// YARACUY
	{
		f.cmbcodmun.options[1]= new Option('ARISTIDES BASTIDAS','001');
		f.cmbcodmun.options[2]= new Option('BOLÍVAR','002');
		f.cmbcodmun.options[3]= new Option('BRUZUAL','003');
		f.cmbcodmun.options[4]= new Option('COCOROTE','004');
		f.cmbcodmun.options[5]= new Option('INDEPENDENCIA','005');
		f.cmbcodmun.options[6]= new Option('JOSÉ ANTONIO PÁEZ','006');
		f.cmbcodmun.options[7]= new Option('LA TRINIDAD','007');
		f.cmbcodmun.options[8]= new Option('MANUEL MONGE','008');
		f.cmbcodmun.options[9]= new Option('NIRGUA','009');
		f.cmbcodmun.options[10]= new Option('PEÑA','010');
		f.cmbcodmun.options[11]= new Option('SAN FELIPE','011');
		f.cmbcodmun.options[12]= new Option('SUCRE','012');
		f.cmbcodmun.options[13]= new Option('URACHICHE','013');
		f.cmbcodmun.options[14]= new Option('VEROES','014');
	}
	if(f.cmbcodent.value=="23")// ZULIA
	{
		f.cmbcodmun.options[1]= new Option('ALMIRANTE PADILLA','001');
		f.cmbcodmun.options[2]= new Option('BARALT','002');
		f.cmbcodmun.options[3]= new Option('CABIMAS','003');
		f.cmbcodmun.options[4]= new Option('CATATUMBO','004');
		f.cmbcodmun.options[5]= new Option('COLÓN','005');
		f.cmbcodmun.options[6]= new Option('FRANCISCO JAVIER PULGAR','006');
		f.cmbcodmun.options[7]= new Option('JESÚS ENRIQUE LOSSADA','007');
		f.cmbcodmun.options[8]= new Option('JESÚS MARÍA SEMPRÚN','008');
		f.cmbcodmun.options[9]= new Option('LA CAÑADA DE URDANETA','009');
		f.cmbcodmun.options[10]= new Option('LAGUNILLAS','010');
		f.cmbcodmun.options[11]= new Option('MACHIQUES DE PERIJÁ','011');
		f.cmbcodmun.options[12]= new Option('MARA','012');
		f.cmbcodmun.options[13]= new Option('MARACAIBO','013');
		f.cmbcodmun.options[14]= new Option('MIRANDA','014');
		f.cmbcodmun.options[15]= new Option('PÁEZ','015');
		f.cmbcodmun.options[16]= new Option('ROSARIO DE PERIJÁ','016');
		f.cmbcodmun.options[17]= new Option('SAN FRANCISCO','017');
		f.cmbcodmun.options[18]= new Option('SANTA RITA','018');
		f.cmbcodmun.options[19]= new Option('SIMÓN BOLÍVAR','019');
		f.cmbcodmun.options[20]= new Option('SUCRE','020');
		f.cmbcodmun.options[21]= new Option('VALMORE RODRÍGUEZ','021');
	}
	if(f.cmbcodent.value=="24")// VARGAS
	{
		f.cmbcodmun.options[1]= new Option('VARGAS','001');
	}
}

function ue_cargarlocalidad()
{
	f=document.form1;
	f.cmbcodloc.length=0;
	f.cmbcodloc.options[0]= new Option('--Seleccione--','');
	if(f.cmbcodent.value=="01")// DTTO FEDERAL
	{
		if(f.cmbcodmun.value=="001")//LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('ALTAGRACIA','001');			
			f.cmbcodloc.options[2]= new Option('ANTIMANO','002');			
			f.cmbcodloc.options[3]= new Option('CANDELARIA','003');			
			f.cmbcodloc.options[4]= new Option('CARICUAO','004');			
			f.cmbcodloc.options[5]= new Option('CATEDRAL','005');			
			f.cmbcodloc.options[6]= new Option('COCHE','006');			
			f.cmbcodloc.options[7]= new Option('EL JUNQUITO','007');			
			f.cmbcodloc.options[8]= new Option('EL PARAISO','008');			
			f.cmbcodloc.options[9]= new Option('EL RECREO','009');			
			f.cmbcodloc.options[10]= new Option('EL VALLE','010');			
			f.cmbcodloc.options[11]= new Option('LA PASTORA','011');			
			f.cmbcodloc.options[12]= new Option('LA VEGA','012');			
			f.cmbcodloc.options[13]= new Option('MACARAO','013');			
			f.cmbcodloc.options[14]= new Option('SAN AGUSTIN','014');			
			f.cmbcodloc.options[15]= new Option('SAN BERNARDINO','015');			
			f.cmbcodloc.options[16]= new Option('SAN JOSE','016');			
			f.cmbcodloc.options[17]= new Option('SAN JUAN','017');			
			f.cmbcodloc.options[18]= new Option('SAN PEDRO','018');			
			f.cmbcodloc.options[19]= new Option('SANTA ROSALIA','019');			
			f.cmbcodloc.options[20]= new Option('SANTA TERESA','020');			
			f.cmbcodloc.options[21]= new Option('SUCRE','021');			
			f.cmbcodloc.options[22]= new Option('23 DE ENERO','022');			
		}
	}
	if(f.cmbcodent.value=="02")// AMAZONAS
	{
		if(f.cmbcodmun.value=="001")// ALTO ORINOCO
		{
			f.cmbcodloc.options[1]= new Option('HUACHAMACARE','001');			
			f.cmbcodloc.options[2]= new Option('MARAWAKA','002');			
			f.cmbcodloc.options[3]= new Option('MAVACA','003');			
			f.cmbcodloc.options[4]= new Option('SIERRA PARIMA','004');			
		}
		if(f.cmbcodmun.value=="002")// ATABAPO
		{
			f.cmbcodloc.options[1]= new Option('UCATA','001');			
			f.cmbcodloc.options[2]= new Option('YACAPANA','002');			
			f.cmbcodloc.options[3]= new Option('CANAME','003');			
		}
		if(f.cmbcodmun.value=="003")// ATURES
		{
			f.cmbcodloc.options[1]= new Option('FERNANDO GIRON TOVAR','001');			
			f.cmbcodloc.options[2]= new Option('LUIS ALBERTO GOMEZ','002');			
			f.cmbcodloc.options[3]= new Option('PARHUEÑA','003');			
			f.cmbcodloc.options[4]= new Option('PLATANILLAL','004');			
		}
		if(f.cmbcodmun.value=="004")// AUTANA
		{
			f.cmbcodloc.options[1]= new Option('SAMARIAPO','001');			
			f.cmbcodloc.options[2]= new Option('SIPAPO','002');			
			f.cmbcodloc.options[3]= new Option('MUNDUAPO','003');			
			f.cmbcodloc.options[4]= new Option('GUAYAPO','004');			
		}
		if(f.cmbcodmun.value=="005")// MAROA
		{
			f.cmbcodloc.options[1]= new Option('VICTORINO','001');			
			f.cmbcodloc.options[2]= new Option('COMUNIDAD','002');			
		}
		if(f.cmbcodmun.value=="006")// MANAPIARE
		{
			f.cmbcodloc.options[1]= new Option('ALTO VENTUARI','001');			
			f.cmbcodloc.options[2]= new Option('MEDIO VENTUARI','002');			
			f.cmbcodloc.options[3]= new Option('BAJO VENTUARI','003');			
		}
		if(f.cmbcodmun.value=="007")// RÍO NEGRO
		{
			f.cmbcodloc.options[1]= new Option('SOLANO','001');			
			f.cmbcodloc.options[2]= new Option('CASIQUIARE','002');			
			f.cmbcodloc.options[3]= new Option('COCUY','003');			
		}
	}	
	if(f.cmbcodent.value=="03")// ANZOATEGUI
	{
		if(f.cmbcodmun.value=="001")//  ANACO
		{
			f.cmbcodloc.options[1]= new Option('ANACO','001');			
			f.cmbcodloc.options[2]= new Option('SAN AGUSTIN','002');			
		}
		if(f.cmbcodmun.value=="002")//  ARAGUA
		{
			f.cmbcodloc.options[1]= new Option('ARAGUA','001');			
			f.cmbcodloc.options[2]= new Option('CACHIPO','002');			
		}
		if(f.cmbcodmun.value=="003")//  FERNANDO DE PEÑALVER
		{
			f.cmbcodloc.options[1]= new Option('PUERTO PIRITU','001');			
			f.cmbcodloc.options[2]= new Option('SAN MIGUEL','002');			
			f.cmbcodloc.options[3]= new Option('SUCRE','003');			
		}
		if(f.cmbcodmun.value=="004")// FRANCISCO DEL CARMEN CARVAJAL
		{
			f.cmbcodloc.options[1]= new Option('VALLE DE GUANAPE','001');			
			f.cmbcodloc.options[2]= new Option('SANTA BARBARA','002');			
		}
		if(f.cmbcodmun.value=="005")//  FRANCISCO DE MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('PARIAGUAN','001');			
			f.cmbcodloc.options[2]= new Option('ATAPIRIRE','002');			
			f.cmbcodloc.options[3]= new Option('BOCA DEL PAO','003');			
			f.cmbcodloc.options[4]= new Option('EL PAO DE BARCELONA','004');			
			f.cmbcodloc.options[5]= new Option('MUCURA','005');			
		}
		if(f.cmbcodmun.value=="006")// GUANTA
		{
			f.cmbcodloc.options[1]= new Option('GUANTA','001');			
			f.cmbcodloc.options[2]= new Option('CHORRERON','002');			
		}
		if(f.cmbcodmun.value=="007")//  INDEPENDENCIA
		{
			f.cmbcodloc.options[1]= new Option('SOLEDAD','001');			
			f.cmbcodloc.options[2]= new Option('MAMO','002');			
		}
		if(f.cmbcodmun.value=="008")// JUAN ANTONIO SOTILLO
		{
			f.cmbcodloc.options[1]= new Option('PUERTO LA CRUZ','001');			
			f.cmbcodloc.options[2]= new Option('POZUELOS','002');			
		}
		if(f.cmbcodmun.value=="009")// JUAN MANUEL CAJIGAL
		{
			f.cmbcodloc.options[1]= new Option('ONOTO','001');			
			f.cmbcodloc.options[2]= new Option('SAN PABLO','002');			
		}
		if(f.cmbcodmun.value=="010")//  JOSE GREGORIO MONAGAS
		{
			f.cmbcodloc.options[1]= new Option('MAPIRE','001');			
			f.cmbcodloc.options[2]= new Option('PIAR','002');			
			f.cmbcodloc.options[3]= new Option('SAN DIEGO DE CABRUTICA','003');			
			f.cmbcodloc.options[4]= new Option('SANTA CLARA','004');			
			f.cmbcodloc.options[5]= new Option('UVERITO','005');			
			f.cmbcodloc.options[6]= new Option('ZUATA','006');			
		}
		if(f.cmbcodmun.value=="011")//  LIBERTAD
		{
			f.cmbcodloc.options[1]= new Option('SAN MATEO','001');			
			f.cmbcodloc.options[2]= new Option('EL CARITO','002');			
			f.cmbcodloc.options[3]= new Option('SANTA INES','003');			
		}
		if(f.cmbcodmun.value=="012")//  MANUEL EZEQUIEL BRUZUAL
		{
			f.cmbcodloc.options[1]= new Option('CLARINES','001');			
			f.cmbcodloc.options[2]= new Option('GUANAPE','002');			
			f.cmbcodloc.options[3]= new Option('SABANA DE UCHIRE','003');			
		}
		if(f.cmbcodmun.value=="013")//  PEDRO MARIA FREITES
		{
			f.cmbcodloc.options[1]= new Option('CANTAURA','001');			
			f.cmbcodloc.options[2]= new Option('LIBERTADOR','002');			
			f.cmbcodloc.options[3]= new Option('SANTA ROSA','003');			
			f.cmbcodloc.options[4]= new Option('URICA','004');			
		}
		if(f.cmbcodmun.value=="014")// PÍRITU
		{
			f.cmbcodloc.options[1]= new Option('PIRITU','001');			
			f.cmbcodloc.options[2]= new Option('SAN FRANCISCO','002');			
		}
		if(f.cmbcodmun.value=="015")//  SAN JOSE DE GUANIPA
		{
		}
		if(f.cmbcodmun.value=="016")//  SAN JUAN DE CAPISTRANO
		{
			f.cmbcodloc.options[1]= new Option('BOCA DE URICHE','001');			
			f.cmbcodloc.options[2]= new Option('BOCA DE CHAVEZ','002');			
		}
		if(f.cmbcodmun.value=="017")//  SANTA ANA
		{
			f.cmbcodloc.options[1]= new Option('SANTA ANA','001');			
			f.cmbcodloc.options[2]= new Option('PUEBLO NUEVO','002');			
		}
		if(f.cmbcodmun.value=="018")//  SIMON BOLIVAR
		{
			f.cmbcodloc.options[1]= new Option('EL CARMEN','001');			
			f.cmbcodloc.options[2]= new Option('SAN CRISTOBAL','002');			
			f.cmbcodloc.options[3]= new Option('BERGATIN','003');			
			f.cmbcodloc.options[4]= new Option('CAIGUA','004');			
			f.cmbcodloc.options[5]= new Option('EL PILAR','005');			
			f.cmbcodloc.options[6]= new Option('NARICUAL','006');			
		}
		if(f.cmbcodmun.value=="019")//  SIMON RODRIGUEZ
		{
			f.cmbcodloc.options[1]= new Option('EDMUNDO BARRIOS','001');			
			f.cmbcodloc.options[2]= new Option('MIGUEL OTERO SILVA','002');			
		}
		if(f.cmbcodmun.value=="020")//  SIR ARTUR MC GREGOR
		{
			f.cmbcodloc.options[1]= new Option('EL CHAPARRO','001');			
			f.cmbcodloc.options[2]= new Option('TOMAS ALFARO CALATRAVA','002');			
		}
		if(f.cmbcodmun.value=="021")//  TURISTICO DIEGO BAUTISTA URBANEJA
		{
			f.cmbcodloc.options[1]= new Option('LECHERIAS','001');			
			f.cmbcodloc.options[2]= new Option('EL MORRO','002');			
		}
	}
	if(f.cmbcodent.value=="04")// APURE
	{
		if(f.cmbcodmun.value=="001")//  ACHAGUAS
		{
			f.cmbcodloc.options[1]= new Option('ACHAGUAS','001');			
			f.cmbcodloc.options[2]= new Option('APURITO','002');			
			f.cmbcodloc.options[3]= new Option('EL YAGUAL','003');			
			f.cmbcodloc.options[4]= new Option('GUACHARA','004');			
			f.cmbcodloc.options[5]= new Option('MUCURITAS','005');			
			f.cmbcodloc.options[6]= new Option('QUESERAS DEL MEDIO','006');			
		}
		if(f.cmbcodmun.value=="002")//  BIRUACA
		{
			f.cmbcodloc.options[1]= new Option('BIRUACA','001');			
		}
		if(f.cmbcodmun.value=="003")//  MUÑOZ
		{
			f.cmbcodloc.options[1]= new Option('BRUZUAL','001');			
			f.cmbcodloc.options[2]= new Option('MANTECAL','002');			
			f.cmbcodloc.options[3]= new Option('QUINTERO','003');			
			f.cmbcodloc.options[4]= new Option('RINCON HONDO','004');			
			f.cmbcodloc.options[5]= new Option('SAN VICENTE','005');			
		}
		
		if(f.cmbcodmun.value=="004")//  PÁEZ
		{
			f.cmbcodloc.options[1]= new Option('GUASDALITO','001');			
			f.cmbcodloc.options[2]= new Option('ARAMENDI','002');			
			f.cmbcodloc.options[3]= new Option('EL AMPARO','003');			
			f.cmbcodloc.options[4]= new Option('SAN CAMILO','004');			
			f.cmbcodloc.options[5]= new Option('URDANETA','005');			
		}
		if(f.cmbcodmun.value=="005")//  PEDRO CAMEJO
		{
			f.cmbcodloc.options[1]= new Option('SAN JUAN DE PAYARA','001');			
			f.cmbcodloc.options[2]= new Option('CODAZZI','002');			
			f.cmbcodloc.options[3]= new Option('CUNAVICHE','003');			
		}
		if(f.cmbcodmun.value=="006")//  RÓMULO GALLEGOS
		{
			f.cmbcodloc.options[1]= new Option('ELORZA','001');			
			f.cmbcodloc.options[2]= new Option('LA TRINIDAD','002');			
		}
		if(f.cmbcodmun.value=="007")//  SAN FERNANDO
		{
			f.cmbcodloc.options[1]= new Option('SAN FERNANDO','001');			
			f.cmbcodloc.options[2]= new Option('EL RECREO','002');			
			f.cmbcodloc.options[3]= new Option('PEÑALVER','003');			
			f.cmbcodloc.options[4]= new Option('SAN RAFAEL DE ATAMAICA','004');			
		}
	}
	if(f.cmbcodent.value=="05")// ARAGUA
	{
		if(f.cmbcodmun.value=="001")//  BOLÍVAR
		{
		}
		if(f.cmbcodmun.value=="002")//  CAMATAGUA
		{
			f.cmbcodloc.options[1]= new Option('CAMATAGUA','001');			
			f.cmbcodloc.options[2]= new Option('CARMEN DE CURA','002');			
		}
		if(f.cmbcodmun.value=="003")//  GIRARDOT
		{
			f.cmbcodloc.options[1]= new Option('CHORONI','001');			
			f.cmbcodloc.options[2]= new Option('LAS DELICIAS','002');			
			f.cmbcodloc.options[3]= new Option('MADRE MARIA DE SAN JOSE','003');			
			f.cmbcodloc.options[4]= new Option('JOAQUIN CRESPO','004');			
			f.cmbcodloc.options[5]= new Option('PEDRO JOSE OVALLES','005');			
			f.cmbcodloc.options[6]= new Option('JOSE CASANOVA GODOY','006');			
			f.cmbcodloc.options[7]= new Option('ANDRES ELOY BLANCO','007');			
			f.cmbcodloc.options[8]= new Option('LOS TACARIGUA','008');			
		}
		if(f.cmbcodmun.value=="004")// JOSÉ ÁNGEL LAMAS
		{
		}
		if(f.cmbcodmun.value=="005")//  JOSÉ FÉLIX RIBAS
		{
			f.cmbcodloc.options[1]= new Option('JOSE FELIZ RIBAS','001');			
			f.cmbcodloc.options[2]= new Option('CASTOR NIEVES RIOS','002');			
			f.cmbcodloc.options[3]= new Option('LAS GUACAMAYAS','003');			
			f.cmbcodloc.options[4]= new Option('PAO DE ZARATE','004');			
			f.cmbcodloc.options[5]= new Option('ZUATA','005');			
		}
		if(f.cmbcodmun.value=="006")//  JOSÉ RAFAEL REVENGA
		{
		}
		if(f.cmbcodmun.value=="007")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('LIBERTADOR','001');			
			f.cmbcodloc.options[2]= new Option('SAN MARTIN DE PORRES','002');			
		}
		if(f.cmbcodmun.value=="008")//  MARIO BRICEÑO IRAGORRY
		{
			f.cmbcodloc.options[1]= new Option('MARIO BRICEÑO IRAGORRY','001');			
			f.cmbcodloc.options[2]= new Option('CAÑA DE AZUCAR','002');			
		}
		if(f.cmbcodmun.value=="009")//  SAN CASIMIRO
		{
			f.cmbcodloc.options[1]= new Option('SAN CASIMIRO','001');			
			f.cmbcodloc.options[2]= new Option('GUIRIPA','002');			
			f.cmbcodloc.options[3]= new Option('OLLAS DE CARAMACATE','003');			
			f.cmbcodloc.options[4]= new Option('VALLE MORIN','004');			
			f.cmbcodloc.options[5]= new Option('','005');			
			f.cmbcodloc.options[6]= new Option('','006');			
			f.cmbcodloc.options[7]= new Option('','007');			
			f.cmbcodloc.options[8]= new Option('','008');			
			f.cmbcodloc.options[9]= new Option('','009');			
			f.cmbcodloc.options[10]= new Option('','010');			
		}
		if(f.cmbcodmun.value=="010")//  SAN SEBASTIÁN
		{
		}
		if(f.cmbcodmun.value=="011")//  SANTIAGO MARIÑO
		{
			f.cmbcodloc.options[1]= new Option('SANTIAGO MARIÑO','001');			
			f.cmbcodloc.options[2]= new Option('AREVALO APONTE','002');			
			f.cmbcodloc.options[3]= new Option('CHUAO','003');			
			f.cmbcodloc.options[4]= new Option('SAMAN DE GUERE','004');			
			f.cmbcodloc.options[5]= new Option('ALFREDO PACHECO MIRANDA','005');			
		}
		if(f.cmbcodmun.value=="012")//  SANTOS MICHELENA
		{
			f.cmbcodloc.options[1]= new Option('SANTOS MICHELENA','001');			
			f.cmbcodloc.options[2]= new Option('TIARA','002');			
		}
		if(f.cmbcodmun.value=="013")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('BELLA VISTA','002');			
		}
		if(f.cmbcodmun.value=="014")//  TOVAR
		{
		}
		if(f.cmbcodmun.value=="015")//  URDANETA
		{
			f.cmbcodloc.options[1]= new Option('URDANETA','001');			
			f.cmbcodloc.options[2]= new Option('LAS PEÑITAS','002');			
			f.cmbcodloc.options[3]= new Option('SAN FRANCISCO DE CARA','003');			
			f.cmbcodloc.options[4]= new Option('TAGUAY','004');			
		}
		if(f.cmbcodmun.value=="016")//  ZAMORA
		{
			f.cmbcodloc.options[1]= new Option('ZAMORA','001');			
			f.cmbcodloc.options[2]= new Option('MAGDALENO','002');			
			f.cmbcodloc.options[3]= new Option('SAN FRANCISCO DE ASIS','003');			
			f.cmbcodloc.options[4]= new Option('VALLES DE TUCUTUNEMO','004');			
			f.cmbcodloc.options[5]= new Option('AUGUSTO MIJARES','005');			
		}
		if(f.cmbcodmun.value=="017")//  FRANCISCO LINARES ALCÁNTARA
		{
			f.cmbcodloc.options[1]= new Option('FRANCISCO LINARES ALCANTARA','001');			
			f.cmbcodloc.options[2]= new Option('FRANCISCO DE MIRANDA','002');			
			f.cmbcodloc.options[3]= new Option('MONSEÑOR FELICIANO GONZALES','003');			
		}
		if(f.cmbcodmun.value=="018")//  OCUMARE DE LA COSTA DE ORO
		{
		}
	}
	if(f.cmbcodent.value=="06")// BARINAS
	{
		if(f.cmbcodmun.value=="001")//  ALBERTO ARVELO TORREALBA
		{
			f.cmbcodloc.options[1]= new Option('SABANETA','001');			
			f.cmbcodloc.options[2]= new Option('RODRIGUEZ DOMINGUEZ','002');			
		}
		if(f.cmbcodmun.value=="002")//  ANTONIO JOSÉ DE SUCRE
		{
			f.cmbcodloc.options[1]= new Option('TICOPORO','001');			
			f.cmbcodloc.options[2]= new Option('ANDRES BELLO','002');			
			f.cmbcodloc.options[3]= new Option('NICOLAS PULIDO','003');			
		}
		if(f.cmbcodmun.value=="003")//  ARISMENDI
		{
			f.cmbcodloc.options[1]= new Option('ARISMENDI','001');			
			f.cmbcodloc.options[2]= new Option('GUADARRAMA','002');			
			f.cmbcodloc.options[3]= new Option('LA UNION','003');			
			f.cmbcodloc.options[4]= new Option('SAN ANTONIO','004');			
		}
		if(f.cmbcodmun.value=="004")//  BARINAS
		{
			f.cmbcodloc.options[1]= new Option('BARINAS','001');			
			f.cmbcodloc.options[2]= new Option('ALFREDO ARVELO LARRIVA','002');			
			f.cmbcodloc.options[3]= new Option('SAN SILVESTRE','003');			
			f.cmbcodloc.options[4]= new Option('SANTA INES','004');			
			f.cmbcodloc.options[5]= new Option('SANTA LUCIA','005');			
			f.cmbcodloc.options[6]= new Option('TORUNOS','006');			
			f.cmbcodloc.options[7]= new Option('EL CARMEN','007');			
			f.cmbcodloc.options[8]= new Option('ROMULO BETANCOURT','008');			
			f.cmbcodloc.options[9]= new Option('CORAZON DE JESUS','009');			
			f.cmbcodloc.options[10]= new Option('RAMON IGNACIO MENDEZ','010');			
			f.cmbcodloc.options[11]= new Option('ALTO BARINAS','011');			
			f.cmbcodloc.options[12]= new Option('MANUEL PALACIOS FAJARDO','012');			
			f.cmbcodloc.options[13]= new Option('JUAN ANTONIO RODRIGUEZ DOMINGUEZ','013');			
			f.cmbcodloc.options[14]= new Option('DOMINGA ORTIZ DE PAEZ','014');			
		}
		if(f.cmbcodmun.value=="005")//  BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('BARINITAS','001');			
			f.cmbcodloc.options[2]= new Option('ALTAMIRA','002');			
			f.cmbcodloc.options[3]= new Option('CALDERAS','003');			
		}
		if(f.cmbcodmun.value=="006")//  CRUZ PAREDES
		{
			f.cmbcodloc.options[1]= new Option('BARRANCAS','001');			
			f.cmbcodloc.options[2]= new Option('EL SOCORRO','002');			
			f.cmbcodloc.options[3]= new Option('MASPARRITO','003');			
		}
		if(f.cmbcodmun.value=="007")//  EZEQUIEL ZAMORA
		{
			f.cmbcodloc.options[1]= new Option('SANTA BARBARA','001');			
			f.cmbcodloc.options[2]= new Option('JOSE IGNACIO DEL PUMAR','002');			
			f.cmbcodloc.options[3]= new Option('PEDRO BRICEÑO MENDEZ','003');			
			f.cmbcodloc.options[4]= new Option('RAMON IGNACIO MENDEZ','004');			
		}
		if(f.cmbcodmun.value=="008")//  OBISPOS
		{
			f.cmbcodloc.options[1]= new Option('OBISPOS','001');			
			f.cmbcodloc.options[2]= new Option('EL REAL','002');			
			f.cmbcodloc.options[3]= new Option('LA LUZ','003');			
			f.cmbcodloc.options[4]= new Option('LOS GUASIMITOS','004');			
		}
		if(f.cmbcodmun.value=="009")//  PEDRAZA
		{
			f.cmbcodloc.options[1]= new Option('CIUDAD BOLIVIA','001');			
			f.cmbcodloc.options[2]= new Option('IGNACIO BRICEÑO','002');			
			f.cmbcodloc.options[3]= new Option('JOSE FELIX RIBAS','003');			
			f.cmbcodloc.options[4]= new Option('PAEZ','004');			
		}
		if(f.cmbcodmun.value=="010")//  ROJAS
		{
			f.cmbcodloc.options[1]= new Option('LIBERTAD','001');			
			f.cmbcodloc.options[2]= new Option('DOLORES','002');			
			f.cmbcodloc.options[3]= new Option('PALACIOS FAJARDO','003');			
			f.cmbcodloc.options[4]= new Option('SANTA ROSA','004');			
		}
		if(f.cmbcodmun.value=="011")//  SOSA
		{
			f.cmbcodloc.options[1]= new Option('CIUDAD DE NUTRIAS','001');			
			f.cmbcodloc.options[2]= new Option('EL REGALO','002');			
			f.cmbcodloc.options[3]= new Option('PUERTO DE NUTRIAS','003');			
			f.cmbcodloc.options[4]= new Option('SANTA CATALINA','004');			
		}
		if(f.cmbcodmun.value=="012")//  ANDRÉS ELOY BLANCO
		{
			f.cmbcodloc.options[1]= new Option('EL CANTON','001');			
			f.cmbcodloc.options[2]= new Option('SANTA CRUZ DE GUACAS','002');			
			f.cmbcodloc.options[3]= new Option('PUERTO VIVAS','003');			
		}
	}
	if(f.cmbcodent.value=="07")// BOLIVAR
	{
		if(f.cmbcodmun.value=="001")//  CARONÍ
		{
			f.cmbcodloc.options[1]= new Option('EL CACHAMAY','001');			
			f.cmbcodloc.options[2]= new Option('EL CHIRICA','002');			
			f.cmbcodloc.options[3]= new Option('DALLA COSTA','003');			
			f.cmbcodloc.options[4]= new Option('ONCE DE ABRIL','004');			
			f.cmbcodloc.options[5]= new Option('SIMON BOLIVAR','005');			
			f.cmbcodloc.options[6]= new Option('UNARE','006');			
			f.cmbcodloc.options[7]= new Option('UNIVERSIDAD','007');			
			f.cmbcodloc.options[8]= new Option('VISTA DEL SOL','008');			
			f.cmbcodloc.options[9]= new Option('POZO VERDE','009');			
			f.cmbcodloc.options[10]= new Option('YOCOIMA','010');			
		}
		if(f.cmbcodmun.value=="002")//  CEDEÑO
		{
			f.cmbcodloc.options[1]= new Option('CEDEÑO','001');			
			f.cmbcodloc.options[2]= new Option('ALTAGRACIA','002');			
			f.cmbcodloc.options[3]= new Option('ASCENCION FARRERAS','003');			
			f.cmbcodloc.options[4]= new Option('GUANIAMO','004');			
			f.cmbcodloc.options[5]= new Option('LA URBANA','005');			
			f.cmbcodloc.options[6]= new Option('PIJIGUAOS','006');			
		}
		if(f.cmbcodmun.value=="003")//  EL CALLAO
		{
		}
		if(f.cmbcodmun.value=="004")//  GRAN SABANA
		{
			f.cmbcodloc.options[1]= new Option('GRAN SABANA','001');			
			f.cmbcodloc.options[2]= new Option('IKABARU','002');			
		}
		if(f.cmbcodmun.value=="005")//  HERES
		{
			f.cmbcodloc.options[1]= new Option('AGUA SALADA','001');			
			f.cmbcodloc.options[2]= new Option('CATEDRAL','002');			
			f.cmbcodloc.options[3]= new Option('JOSE ANTONIO PAEZ','003');			
			f.cmbcodloc.options[4]= new Option('LA SABANITA','004');			
			f.cmbcodloc.options[5]= new Option('MARHUANTA','005');			
			f.cmbcodloc.options[6]= new Option('VISTA HERMOSA','006');			
			f.cmbcodloc.options[7]= new Option('ORINOCO','007');			
			f.cmbcodloc.options[8]= new Option('PANAPANA','008');			
			f.cmbcodloc.options[9]= new Option('ZEA','009');			
		}
		if(f.cmbcodmun.value=="006")//  PIAR
		{
			f.cmbcodloc.options[1]= new Option('PIAR','001');			
			f.cmbcodloc.options[2]= new Option('ANDRES ELOY BLANCO','002');			
			f.cmbcodloc.options[3]= new Option('PEDRO COVA','003');			
		}
		if(f.cmbcodmun.value=="007")//  RAÚL LEONI
		{
			f.cmbcodloc.options[1]= new Option('RAUL LEONI','001');			
			f.cmbcodloc.options[2]= new Option('BARCELONETA','002');			
			f.cmbcodloc.options[3]= new Option('SAN FRANCISCO','003');			
			f.cmbcodloc.options[4]= new Option('SANTA BARBARA','004');			
		}
		if(f.cmbcodmun.value=="008")//  ROSCIO
		{
			f.cmbcodloc.options[1]= new Option('ROSCIO','001');			
			f.cmbcodloc.options[2]= new Option('SALOM','002');			
		}
		if(f.cmbcodmun.value=="009")//  SIFONTES
		{
			f.cmbcodloc.options[1]= new Option('SIFONTES','001');			
			f.cmbcodloc.options[2]= new Option('DALLA COSTA','002');			
			f.cmbcodloc.options[3]= new Option('SAN ISIDRO','003');			
		}
		if(f.cmbcodmun.value=="010")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('ARIPAO','002');			
			f.cmbcodloc.options[3]= new Option('GUARATARO','003');			
			f.cmbcodloc.options[4]= new Option('LAS MAJADAS','004');			
			f.cmbcodloc.options[5]= new Option('MOITACO','005');			
		}
		if(f.cmbcodmun.value=="011")// PADRE PEDRO CHIEN
		{
		}
	}
	if(f.cmbcodent.value=="08")// CARABOBO
	{
		if(f.cmbcodmun.value=="001")//  BEJUMA
		{
			f.cmbcodloc.options[1]= new Option('BEJUMA','001');			
			f.cmbcodloc.options[2]= new Option('CANOABO','002');			
			f.cmbcodloc.options[3]= new Option('SIMON BOLIVAR','003');			
		}
		if(f.cmbcodmun.value=="002")//  CARLOS ARVELO
		{
			f.cmbcodloc.options[1]= new Option('GUIGUE','001');			
			f.cmbcodloc.options[2]= new Option('BELEN','002');			
			f.cmbcodloc.options[3]= new Option('TACARIGUA','003');			
		}
		if(f.cmbcodmun.value=="003")//  DIEGO IBARRA
		{
			f.cmbcodloc.options[1]= new Option('AGUAS CALIENTES','001');			
			f.cmbcodloc.options[2]= new Option('MARIARA','002');			
		}
		if(f.cmbcodmun.value=="004")//  GUACARA
		{
			f.cmbcodloc.options[1]= new Option('CIUDAD ALIANZA','001');			
			f.cmbcodloc.options[2]= new Option('GUACARA','002');			
			f.cmbcodloc.options[3]= new Option('YAGUA','003');			
		}
		if(f.cmbcodmun.value=="005")//  JUAN JOSÉ MORA
		{
			f.cmbcodloc.options[1]= new Option('MORON','001');			
			f.cmbcodloc.options[2]= new Option('URAMA','002');			
		}
		if(f.cmbcodmun.value=="006")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('TOCUYITO','001');			
			f.cmbcodloc.options[2]= new Option('INDEPENDENCIA','002');			
		}
		if(f.cmbcodmun.value=="007")//  LOS GUAYOS
		{
			f.cmbcodloc.options[1]= new Option('LOS GUAYOS','001');			
		}
		if(f.cmbcodmun.value=="008")//  MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('MIRANDA','001');			
		}
		if(f.cmbcodmun.value=="009")//  MONTALBÁN
		{
			f.cmbcodloc.options[1]= new Option('MONTALBAN','001');			
		}
		if(f.cmbcodmun.value=="010")//  NAGUANAGUA
		{
			f.cmbcodloc.options[1]= new Option('NAGUANAGUA','001');			
		}
		if(f.cmbcodmun.value=="011")//  PUERTO CABELLO
		{
			f.cmbcodloc.options[1]= new Option('BARTOLOME SALOM','001');			
			f.cmbcodloc.options[2]= new Option('DEMOCRACIA','002');			
			f.cmbcodloc.options[3]= new Option('FRATERNIDAD','003');			
			f.cmbcodloc.options[4]= new Option('GOAIGOAZA','004');			
			f.cmbcodloc.options[5]= new Option('JUAN JOSE FLORES','005');			
			f.cmbcodloc.options[6]= new Option('UNION','006');			
			f.cmbcodloc.options[7]= new Option('BORBURATA','007');			
			f.cmbcodloc.options[8]= new Option('PATANEMO','008');			
		}
		if(f.cmbcodmun.value=="012")//  SAN DIEGO
		{
			f.cmbcodloc.options[1]= new Option('SAN DIEGO','001');			
		}
		if(f.cmbcodmun.value=="013")//  SAN JOAQUÍN
		{
			f.cmbcodloc.options[1]= new Option('SAN JOAQUIN','001');			
		}
		if(f.cmbcodmun.value=="014")//  VALENCIA
		{
			f.cmbcodloc.options[1]= new Option('CANDELARIA','001');			
			f.cmbcodloc.options[2]= new Option('CATEDRAL','002');			
			f.cmbcodloc.options[3]= new Option('EL SOCORRO','003');			
			f.cmbcodloc.options[4]= new Option('MIGUEL PEÑA','004');			
			f.cmbcodloc.options[5]= new Option('RAFAEL URDANETA','005');			
			f.cmbcodloc.options[6]= new Option('SAN BLAS','006');			
			f.cmbcodloc.options[7]= new Option('SAN JOSE','007');			
			f.cmbcodloc.options[8]= new Option('SANTA ROSA','008');			
			f.cmbcodloc.options[9]= new Option('NEGRO PRIMERO','009');			
		}
	}
	if(f.cmbcodent.value=="09")// COJEDES
	{
		if(f.cmbcodmun.value=="001")//  ANZOÁTEGUI
		{
			f.cmbcodloc.options[1]= new Option('COJEDES','001');			
			f.cmbcodloc.options[2]= new Option('JUAN DE MATA SUAREZ','002');			
		}
		if(f.cmbcodmun.value=="002")//  FALCÓN
		{
			f.cmbcodloc.options[1]= new Option('TINAQUILLO','001');			
		}
		if(f.cmbcodmun.value=="003")//  GIRARDOT
		{
			f.cmbcodloc.options[1]= new Option('EL BAUL','001');			
			f.cmbcodloc.options[2]= new Option('SUCRE','002');			
		}
		if(f.cmbcodmun.value=="004")//  LIMA BLANCO
		{
			f.cmbcodloc.options[1]= new Option('MACAPO','001');			
			f.cmbcodloc.options[2]= new Option('LA AGUADITA','002');			
		}
		if(f.cmbcodmun.value=="005")//  EL PAO DE SAN JUAN BAUTISTA
		{
			f.cmbcodloc.options[1]= new Option('EL PAO','001');			
		}		
		if(f.cmbcodmun.value=="006")//  RICAURTE
		{
			f.cmbcodloc.options[1]= new Option('LIBERTAD DE COJEDES','001');			
			f.cmbcodloc.options[2]= new Option('EL AMPARO','002');			
		}
	
		if(f.cmbcodmun.value=="007")//  RÓMULO GALLEGOS
		{
			f.cmbcodloc.options[1]= new Option('ROMULO GALLEGOS','001');			
		}
		if(f.cmbcodmun.value=="008")//  SAN CARLOS DE AUSTRIA
		{
			f.cmbcodloc.options[1]= new Option('SAN CARLOS DE AUSTRIA','001');			
			f.cmbcodloc.options[2]= new Option('JUAN ANGEL BRAVO','002');			
			f.cmbcodloc.options[3]= new Option('MANUEL MANRIQUE','003');			
		}
		if(f.cmbcodmun.value=="009")//  TINACO
		{
			f.cmbcodloc.options[1]= new Option('GENERAL EN JEFE JOSE','001');			
			f.cmbcodloc.options[2]= new Option('LAURENCIO SILVA','002');			
		}		
	}
	if(f.cmbcodent.value=="10")// DELTA AMACURO
	{
		if(f.cmbcodmun.value=="001")// ANTONIO DÍAZ
		{
			f.cmbcodloc.options[1]= new Option('CURIAPO','001');			
			f.cmbcodloc.options[2]= new Option('ALMIRANTE LUIS BRION','002');			
			f.cmbcodloc.options[3]= new Option('FRANCISCO ANICETO LUGO','003');			
			f.cmbcodloc.options[4]= new Option('MANUEL RENAUD','004');			
			f.cmbcodloc.options[5]= new Option('PADRE BARRAL','005');			
			f.cmbcodloc.options[6]= new Option('SANTOS DE ABELGAS','006');			
		}
		if(f.cmbcodmun.value=="002")//  CASACOIMA
		{
			f.cmbcodloc.options[1]= new Option('IMATACA','001');			
			f.cmbcodloc.options[2]= new Option('CINCO DE JULIO','002');			
			f.cmbcodloc.options[3]= new Option('JUAN BAUTISTA ARISMENDI','003');			
			f.cmbcodloc.options[4]= new Option('MANUEL PIAR','004');			
			f.cmbcodloc.options[5]= new Option('ROMULO GALLEGOS','005');			
		}
		if(f.cmbcodmun.value=="003")// PEDERNALES
		{
			f.cmbcodloc.options[1]= new Option('PEDERNALES','001');			
			f.cmbcodloc.options[2]= new Option('LUIS BELTRAN PRIETO FIGUEROA','002');			
		}
		if(f.cmbcodmun.value=="004")//  TUCUPITA
		{
			f.cmbcodloc.options[1]= new Option('SAN JOSE','001');			
			f.cmbcodloc.options[2]= new Option('JOSE VIDAL MARCANO','002');			
			f.cmbcodloc.options[3]= new Option('JUAN MILLAN','003');			
			f.cmbcodloc.options[4]= new Option('LEONARDO LUIS PINEDA','004');			
			f.cmbcodloc.options[5]= new Option('MARICAL ANTONIO JOSE DE SUCRE','005');			
			f.cmbcodloc.options[6]= new Option('MONSEÑOR ARGUIMIRO GARCIA','006');			
			f.cmbcodloc.options[7]= new Option('SAN RAFAEL','007');			
			f.cmbcodloc.options[8]= new Option('VIRGEN DEL VALLE','008');			
		}
	}
	if(f.cmbcodent.value=="11")// FALCON
	{
		if(f.cmbcodmun.value=="001")//  ACOSTA
		{
			f.cmbcodloc.options[1]= new Option('SAN JUAN DE LOS CAYOS','001');			
			f.cmbcodloc.options[2]= new Option('CAPADARE','002');			
			f.cmbcodloc.options[3]= new Option('LA PASTORA','003');			
			f.cmbcodloc.options[4]= new Option('LIBERTADOR','004');			
		}
		if(f.cmbcodmun.value=="002")//  BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('SAN LUIS','001');			
			f.cmbcodloc.options[2]= new Option('ARACUA','002');			
			f.cmbcodloc.options[3]= new Option('LA PEÑA','003');			
		}
		if(f.cmbcodmun.value=="003")//  BUCHIVACOA
		{
			f.cmbcodloc.options[1]= new Option('CAPATARIDA','001');			
			f.cmbcodloc.options[2]= new Option('BARIRO','002');			
			f.cmbcodloc.options[3]= new Option('BOROJO','003');			
			f.cmbcodloc.options[4]= new Option('GUAJIRO','004');			
			f.cmbcodloc.options[5]= new Option('SEQUE','005');			
			f.cmbcodloc.options[6]= new Option('ZAZARIDA','006');			
		}
		if(f.cmbcodmun.value=="004")//  CACIQUE MANAURE
		{
		}
		if(f.cmbcodmun.value=="005")//  CARIRUBANA
		{
			f.cmbcodloc.options[1]= new Option('CARIRUBANA','001');			
			f.cmbcodloc.options[2]= new Option('NORTE','002');			
			f.cmbcodloc.options[3]= new Option('PUNTA CARDON','003');			
			f.cmbcodloc.options[4]= new Option('SANTA ANA','004');			
		}
		if(f.cmbcodmun.value=="006")//  COLINA
		{
			f.cmbcodloc.options[1]= new Option('LA VELA DE CORO','001');			
			f.cmbcodloc.options[2]= new Option('ACURIGUA','002');			
			f.cmbcodloc.options[3]= new Option('GUAIBACOA','003');			
			f.cmbcodloc.options[4]= new Option('LAS CALDERAS','004');			
			f.cmbcodloc.options[5]= new Option('MACORUCA','005');			
		}
		if(f.cmbcodmun.value=="007")//  DABAJURO
		{
		}
		if(f.cmbcodmun.value=="008")//  DEMOCRACIA
		{
			f.cmbcodloc.options[1]= new Option('PEDREGAL','001');			
			f.cmbcodloc.options[2]= new Option('AGUA CLARA','002');			
			f.cmbcodloc.options[3]= new Option('AVARIA','003');			
			f.cmbcodloc.options[4]= new Option('PIEDRA GRANDE','004');			
			f.cmbcodloc.options[5]= new Option('PURURECHE','005');			
		}
		if(f.cmbcodmun.value=="009")//  FALCÓN
		{
			f.cmbcodloc.options[1]= new Option('PUEBLO NUEVO','001');			
			f.cmbcodloc.options[2]= new Option('ADICORA','002');			
			f.cmbcodloc.options[3]= new Option('BARAIVED','003');			
			f.cmbcodloc.options[4]= new Option('BUENA VISTA','004');			
			f.cmbcodloc.options[5]= new Option('JADACAQUIVA','005');			
			f.cmbcodloc.options[6]= new Option('MORUY','006');			
			f.cmbcodloc.options[7]= new Option('ADAURE','007');			
			f.cmbcodloc.options[8]= new Option('EL HATO','008');			
			f.cmbcodloc.options[9]= new Option('EL VINCULO','009');			
		}
		if(f.cmbcodmun.value=="010")//  FEDERACIÓN
		{
			f.cmbcodloc.options[1]= new Option('CHURUGUARA','001');			
			f.cmbcodloc.options[2]= new Option('AGUA LARGA','002');			
			f.cmbcodloc.options[3]= new Option('PAUJI','003');			
			f.cmbcodloc.options[4]= new Option('INDEPENDENCIA','004');			
			f.cmbcodloc.options[5]= new Option('MAPARARI','005');			
		}
		if(f.cmbcodmun.value=="011")//  JACURA
		{
			f.cmbcodloc.options[1]= new Option('JACURA','001');			
			f.cmbcodloc.options[2]= new Option('AGUA LINDA','002');			
			f.cmbcodloc.options[3]= new Option('ARAURIMA','003');			
		}
		if(f.cmbcodmun.value=="012")//  LOS TAQUES
		{
			f.cmbcodloc.options[1]= new Option('LOS TAQUES','001');			
			f.cmbcodloc.options[2]= new Option('JUDIBANA','002');			
		}
		if(f.cmbcodmun.value=="013")//  MAUROA
		{
			f.cmbcodloc.options[1]= new Option('MENE DE MAUROA','001');			
			f.cmbcodloc.options[2]= new Option('CASIGUA','002');			
			f.cmbcodloc.options[3]= new Option('SAN FELIX','003');			
		}
		if(f.cmbcodmun.value=="014")//  MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('SAN ANTONIO','001');			
			f.cmbcodloc.options[2]= new Option('SAN GABRIEL','002');			
			f.cmbcodloc.options[3]= new Option('SANTA ANA','003');			
			f.cmbcodloc.options[4]= new Option('GUZMAN GUILLERMO','004');			
			f.cmbcodloc.options[5]= new Option('MITARE','005');			
			f.cmbcodloc.options[6]= new Option('RIO SECO','006');			
			f.cmbcodloc.options[7]= new Option('SABANETA','007');			
		}
		if(f.cmbcodmun.value=="015")//  MONSEÑOR ITURRIZA
		{
			f.cmbcodloc.options[1]= new Option('CHICHIRIVICHE','001');			
			f.cmbcodloc.options[2]= new Option('BOCA DE TOCUYO','002');			
			f.cmbcodloc.options[3]= new Option('TOCUYO DE LA COSTA','003');			
		}
		if(f.cmbcodmun.value=="016")//  PALMASOLA
		{
		}
		if(f.cmbcodmun.value=="017")//  PETIT
		{
			f.cmbcodloc.options[1]= new Option('CABURE','001');			
			f.cmbcodloc.options[2]= new Option('COLINA','002');			
			f.cmbcodloc.options[3]= new Option('CURIMAGUA','003');			
		}
		if(f.cmbcodmun.value=="018")//  PÍRITU
		{
			f.cmbcodloc.options[1]= new Option('PIRITU','001');			
			f.cmbcodloc.options[2]= new Option('SAN JOSE DE LA COSTA','002');			
		}
		if(f.cmbcodmun.value=="019")//  SAN FRANCISCO
		{
		}
		if(f.cmbcodmun.value=="020")//  SILVA
		{
			f.cmbcodloc.options[1]= new Option('TUCACAS','001');			
			f.cmbcodloc.options[2]= new Option('BOCA DE AROA','002');			
		}
		if(f.cmbcodmun.value=="021")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('PECAYA','002');			
		}
		if(f.cmbcodmun.value=="022")//  TOCOPERO
		{
			f.cmbcodloc.options[1]= new Option('TOCOPERO','001');			
		}
		if(f.cmbcodmun.value=="023")//  UNIÓN
		{
			f.cmbcodloc.options[1]= new Option('SANTA CRUZ DE BUCARAL','001');			
			f.cmbcodloc.options[2]= new Option('EL CHARAL','002');			
			f.cmbcodloc.options[3]= new Option('LAS VEGAS DEL TUY','003');			
		}
		if(f.cmbcodmun.value=="024")//  URUMACO
		{
			f.cmbcodloc.options[1]= new Option('URUMACO','001');			
			f.cmbcodloc.options[2]= new Option('BRUZUAL','002');			
		}
		if(f.cmbcodmun.value=="025")//  ZAMORA
		{
			f.cmbcodloc.options[1]= new Option('PUERTO CUMAREBO','001');			
			f.cmbcodloc.options[2]= new Option('LA CIENAGA','002');			
			f.cmbcodloc.options[3]= new Option('LA SOLEDAD','003');			
			f.cmbcodloc.options[4]= new Option('PUEBLO CUMAREBO','004');			
			f.cmbcodloc.options[5]= new Option('ZAZARIDA','005');			
		}
	}
	if(f.cmbcodent.value=="12")// GUARICO
	{
		if(f.cmbcodmun.value=="001")// CAMAGUAN
		{
			f.cmbcodloc.options[1]= new Option('CAMAGUAN','001');			
			f.cmbcodloc.options[2]= new Option('PUERTO MIRANDA','002');			
			f.cmbcodloc.options[3]= new Option('UVERITO','003');			
		}
		if(f.cmbcodmun.value=="002")//  CHAGUARAMAS
		{
			f.cmbcodloc.options[1]= new Option('CHAGUARAMAS','001');			
		}
		if(f.cmbcodmun.value=="003")//  EL SOCORRO
		{
			f.cmbcodloc.options[1]= new Option('EL SOCORRO','001');			
		}
		if(f.cmbcodmun.value=="004")//  SAN GERONIMO DE GUAYABAL
		{
			f.cmbcodloc.options[1]= new Option('SAN GERONIMO DE GUAYABAL','001');			
			f.cmbcodloc.options[2]= new Option('CAZORLA','002');			
		}
		if(f.cmbcodmun.value=="005")//  LEONARDO INFANTE
		{
			f.cmbcodloc.options[1]= new Option('VALLE DE LA PASCUA','001');			
			f.cmbcodloc.options[2]= new Option('ESPINO','002');			
		}
		if(f.cmbcodmun.value=="006")//  LAS MERCEDES
		{
			f.cmbcodloc.options[1]= new Option('LAS MERCEDES','001');			
			f.cmbcodloc.options[2]= new Option('CABRUTA','002');			
			f.cmbcodloc.options[3]= new Option('SANTA RITA MANAPIRE','003');			
		}
		if(f.cmbcodmun.value=="007")//  JULIAN MECHADO
		{
			f.cmbcodloc.options[1]= new Option('EL SOMBRERO','001');			
			f.cmbcodloc.options[2]= new Option('SOSA','002');			
		}
		if(f.cmbcodmun.value=="008")//  FRANCISCO DE MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('CALABOZO','001');			
			f.cmbcodloc.options[2]= new Option('EL CALVARIO','002');			
			f.cmbcodloc.options[3]= new Option('EL RASTRO','003');			
			f.cmbcodloc.options[4]= new Option('GUARDATINAJAS','004');			
		}
		if(f.cmbcodmun.value=="009")//  JOSE TADEO MONAGAS
		{
			f.cmbcodloc.options[1]= new Option('ALTAGRACIA DE ORITUCO','001');			
			f.cmbcodloc.options[2]= new Option('LEZAMA','002');			
			f.cmbcodloc.options[3]= new Option('LIBERTAD DE ORITUCO','003');			
			f.cmbcodloc.options[4]= new Option('PASO REAL DE MACAIRA','004');			
			f.cmbcodloc.options[5]= new Option('SAN FRANCISCO DE MACAIRA','005');			
			f.cmbcodloc.options[6]= new Option('SAN RAFAEL DE ORITUCO','006');			
			f.cmbcodloc.options[7]= new Option('SOUBLETTE','007');			
		}
		if(f.cmbcodmun.value=="010")//  ORTIZ
		{
			f.cmbcodloc.options[1]= new Option('ORTIZ','001');			
			f.cmbcodloc.options[2]= new Option('SAN FRANCISCO DE TIZNADOS','002');			
			f.cmbcodloc.options[3]= new Option('SAN JOSE DE TIZNADOS','003');			
			f.cmbcodloc.options[4]= new Option('SAN LORENZO DE TIZNADOS','004');			
		}
		if(f.cmbcodmun.value=="011")//  JOSE FELIX RIBAS
		{
			f.cmbcodloc.options[1]= new Option('TUCUPIDO','001');			
			f.cmbcodloc.options[2]= new Option('SAN RAFAEL DE LAYA','002');			
		}
		if(f.cmbcodmun.value=="012")//  JUAN GERMAN ROSCIO
		{
			f.cmbcodloc.options[1]= new Option('SAN JUAN DE LOS MORROS','001');			
			f.cmbcodloc.options[2]= new Option('CANTAGALLO','002');			
			f.cmbcodloc.options[3]= new Option('PARAPARA','003');			
		}
		if(f.cmbcodmun.value=="013")//  SAN JOSE DE GUARIBE 
		{
			f.cmbcodloc.options[1]= new Option('SAN JOSE DE GUARIBE','001');			
		}
		if(f.cmbcodmun.value=="014")//  SANTA MARIA DE IPIRE
		{
			f.cmbcodloc.options[1]= new Option('SANTA MARIA DE IPIRE','001');			
			f.cmbcodloc.options[2]= new Option('ALTAMIRA','002');			
		}
		if(f.cmbcodmun.value=="015")//  PEDRO ZARAZA
		{
			f.cmbcodloc.options[1]= new Option('ZARAZA','001');			
			f.cmbcodloc.options[2]= new Option('SAN JOSE DE UNARE','002');			
		}
	}
	if(f.cmbcodent.value=="13")// LARA
	{
		if(f.cmbcodmun.value=="001")//  ANDRÉS ELOY BLANCO
		{
			f.cmbcodloc.options[1]= new Option('PIO TAMACHO','001');			
			f.cmbcodloc.options[2]= new Option('QUEBRADA HONDA DE GUACHE','002');			
			f.cmbcodloc.options[3]= new Option('YACAMBU','003');			
		}
		if(f.cmbcodmun.value=="002")//  CRESPO
		{
			f.cmbcodloc.options[1]= new Option('FREITEZ','001');			
			f.cmbcodloc.options[2]= new Option('JOSE MARIA BLANCO','002');			
		}
		if(f.cmbcodmun.value=="003")//  IRIBARREN
		{
			f.cmbcodloc.options[1]= new Option('CATEDRAL','001');			
			f.cmbcodloc.options[2]= new Option('CONCEPCION','002');			
			f.cmbcodloc.options[3]= new Option('EL CUJI','003');			
			f.cmbcodloc.options[4]= new Option('JUAN DE VILLEGAS','004');			
			f.cmbcodloc.options[5]= new Option('SANTA ROSA','005');			
			f.cmbcodloc.options[6]= new Option('TAMACA','006');			
			f.cmbcodloc.options[7]= new Option('UNION','007');			
			f.cmbcodloc.options[8]= new Option('AGUEDO FELIPE ALVARADO','008');			
			f.cmbcodloc.options[9]= new Option('BUENA VISTA','009');			
			f.cmbcodloc.options[10]= new Option('JUAREZ','010');			
		}
		if(f.cmbcodmun.value=="004")//  JIMÉNEZ
		{
			f.cmbcodloc.options[1]= new Option('JUAN BAUTISTA RODRIGUEZ','001');			
			f.cmbcodloc.options[2]= new Option('CUARA','002');			
			f.cmbcodloc.options[3]= new Option('DIEGO DE LOZADA','003');			
			f.cmbcodloc.options[4]= new Option('PARAISO DE SAN JOSE','004');			
			f.cmbcodloc.options[5]= new Option('SAN MIGUEL','005');			
			f.cmbcodloc.options[6]= new Option('TINTORERO','006');			
			f.cmbcodloc.options[7]= new Option('JOSE BERNARDO DURANTE','007');			
			f.cmbcodloc.options[8]= new Option('CORONEL MARIANO PERAZA','008');			
		}
		if(f.cmbcodmun.value=="005")// MORÁN
		{
			f.cmbcodloc.options[1]= new Option('BOLIVAR','001');			
			f.cmbcodloc.options[2]= new Option('ANZOATEGUI','002');			
			f.cmbcodloc.options[3]= new Option('GUARICO','003');			
			f.cmbcodloc.options[4]= new Option('HILARIO LUNA Y LUNA','004');			
			f.cmbcodloc.options[5]= new Option('HUMUCARO ALTO','005');			
			f.cmbcodloc.options[6]= new Option('HUMUCARO BAJO','006');			
			f.cmbcodloc.options[7]= new Option('LA CANDELARIA','007');			
			f.cmbcodloc.options[8]= new Option('MORAN','008');			
		}
		if(f.cmbcodmun.value=="006")//  PALAVECINO
		{
			f.cmbcodloc.options[1]= new Option('CABUDARE','001');			
			f.cmbcodloc.options[2]= new Option('JOSE GREGORIO BASTIDAS','002');			
			f.cmbcodloc.options[3]= new Option('AGUA VIVA','003');			
		}
		if(f.cmbcodmun.value=="007")//  SIMÓN PLANAS
		{
			f.cmbcodloc.options[1]= new Option('SARARE','001');			
			f.cmbcodloc.options[2]= new Option('BURIA','002');			
			f.cmbcodloc.options[3]= new Option('GUSTAVO VEGAS LEON','003');			
		}
		if(f.cmbcodmun.value=="008")//  TORRES
		{
			f.cmbcodloc.options[1]= new Option('TRINIDAD SAMUEL','001');			
			f.cmbcodloc.options[2]= new Option('ANTONIO DIAZ','002');			
			f.cmbcodloc.options[3]= new Option('CAMACARO','003');			
			f.cmbcodloc.options[4]= new Option('CASTAÑEDA','004');			
			f.cmbcodloc.options[5]= new Option('CECILIO ZUBILLAGA','005');			
			f.cmbcodloc.options[6]= new Option('CHIQUINQUIRA','006');			
			f.cmbcodloc.options[7]= new Option('EL BLANCO','007');			
			f.cmbcodloc.options[8]= new Option('ESPINOZA DE LOS MONTEROS','008');			
			f.cmbcodloc.options[9]= new Option('LARA','009');			
			f.cmbcodloc.options[10]= new Option('LAS MERCEDES','010');			
			f.cmbcodloc.options[11]= new Option('MANUEL MORILLO','011');			
			f.cmbcodloc.options[12]= new Option('MONTAÑA VERDE','012');			
			f.cmbcodloc.options[13]= new Option('MONTES DE OCA','013');			
			f.cmbcodloc.options[14]= new Option('TORREZ','014');			
			f.cmbcodloc.options[15]= new Option('HERIBERTO ARROYO','015');			
			f.cmbcodloc.options[16]= new Option('REYES VARCAS','016');			
			f.cmbcodloc.options[17]= new Option('ALTAGRACIA','017');			
		}
		if(f.cmbcodmun.value=="009")//  URDANETA
		{
			f.cmbcodloc.options[1]= new Option('SIQUISIQUE','001');			
			f.cmbcodloc.options[2]= new Option('MOROTURO','002');			
			f.cmbcodloc.options[3]= new Option('SAN MIGUEL','003');			
			f.cmbcodloc.options[4]= new Option('XAGUAS','004');			
		}
	}
	if(f.cmbcodent.value=="14")// MERIDA
	{
		if(f.cmbcodmun.value=="001")//  ALBERTO ADRIANI
		{
			f.cmbcodloc.options[1]= new Option('PRESIDENTE BETANCOURD','001');			
			f.cmbcodloc.options[2]= new Option('PRESIENTE PAEZ','002');			
			f.cmbcodloc.options[3]= new Option('PRESIDENTE ROMULO GALLEGOS','003');			
			f.cmbcodloc.options[4]= new Option('GABRIEL PICON GONZALEZ','004');			
			f.cmbcodloc.options[5]= new Option('HECTOR AMABLE MORA','005');			
			f.cmbcodloc.options[6]= new Option('JOSE NUCETE SARDI','006');			
			f.cmbcodloc.options[7]= new Option('PULIDO MENDEZ','007');			
		}
		if(f.cmbcodmun.value=="002")//  ANDRÉS BELLO
		{
		}
		if(f.cmbcodmun.value=="003")//  ANTONIO PINTO SALINAS
		{
			f.cmbcodloc.options[1]= new Option('PINTO SALINAS','001');			
			f.cmbcodloc.options[2]= new Option('MESA BOLIVAR','002');			
			f.cmbcodloc.options[3]= new Option('MESA DE LAS PALMAS','003');			
		}
		if(f.cmbcodmun.value=="004")//  ARICAGUA
		{
			f.cmbcodloc.options[1]= new Option('ARICAGUA','001');			
			f.cmbcodloc.options[2]= new Option('SAN ANTONIO','002');			
		}
		if(f.cmbcodmun.value=="005")//  ARZOBISPO CHACÓN
		{
			f.cmbcodloc.options[1]= new Option('ARZOBISPO CHACÓN','001');			
			f.cmbcodloc.options[2]= new Option('CAPURI','002');			
			f.cmbcodloc.options[3]= new Option('CHACANTA','003');			
			f.cmbcodloc.options[4]= new Option('EL MOLINO','004');			
			f.cmbcodloc.options[5]= new Option('GUAIMARAL','005');			
			f.cmbcodloc.options[6]= new Option('MUCUTUY','006');			
			f.cmbcodloc.options[7]= new Option('MUCUCHACHI','007');			
		}
		if(f.cmbcodmun.value=="006")//  CAMPO ELÍAS
		{
			f.cmbcodloc.options[1]= new Option('FERNANDEZ PEÑA','001');			
			f.cmbcodloc.options[2]= new Option('MATRIZ','002');			
			f.cmbcodloc.options[3]= new Option('MONTALBAN','003');			
			f.cmbcodloc.options[4]= new Option('ACEQUIAS','004');			
			f.cmbcodloc.options[5]= new Option('JAJI','005');			
			f.cmbcodloc.options[6]= new Option('LA MESA','006');			
			f.cmbcodloc.options[7]= new Option('SAN JOSE','007');			
		}
		if(f.cmbcodmun.value=="007")//  CARACCIOLO PARRA OLMEDO
		{
			f.cmbcodloc.options[1]= new Option('CARACCIOLO PARRA OLMEDO','001');			
			f.cmbcodloc.options[2]= new Option('FLORENCIO RAMIREZ','002');			
		}
		if(f.cmbcodmun.value=="008")//  CARDENAL QUINTERO
		{
			f.cmbcodloc.options[1]= new Option('CARDENAL QUINTERO','001');			
			f.cmbcodloc.options[2]= new Option('LAS PIEDRAS','002');			
		}
		if(f.cmbcodmun.value=="009")//  GUARAQUE
		{
			f.cmbcodloc.options[1]= new Option('GUARAQUE','001');			
			f.cmbcodloc.options[2]= new Option('MESA DE QUINTERO','002');			
			f.cmbcodloc.options[3]= new Option('RIO NEGRO','003');			
		}
		if(f.cmbcodmun.value=="010")//  JULIO CÉSAR SALAS
		{
			f.cmbcodloc.options[1]= new Option('JULIO CÉSAR SALAS','001');			
			f.cmbcodloc.options[2]= new Option('PALMIRA','002');			
		}
		if(f.cmbcodmun.value=="011")//  JUSTO BRICEÑO
		{
			f.cmbcodloc.options[1]= new Option('JUSTO BRICEÑO','001');			
			f.cmbcodloc.options[2]= new Option('SAN CRISTOBAL DE TORONDOY','002');			
		}
		if(f.cmbcodmun.value=="012")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('ANTONIO SPINETTI DINI','001');			
			f.cmbcodloc.options[2]= new Option('ARIAS','002');			
			f.cmbcodloc.options[3]= new Option('CARACCIOLO PARRA PEREZ','003');			
			f.cmbcodloc.options[4]= new Option('DOMINGO PEÑA','004');			
			f.cmbcodloc.options[5]= new Option('EL LLANO','005');			
			f.cmbcodloc.options[6]= new Option('GONZALO PICON FEBRES','006');			
			f.cmbcodloc.options[7]= new Option('JACINTO PLAZA','007');			
			f.cmbcodloc.options[8]= new Option('JUAN RODRIGUEZ SUAREZ','008');			
			f.cmbcodloc.options[9]= new Option('LASSO DE LA VEGA','009');			
			f.cmbcodloc.options[10]= new Option('MARIANO PICON SALAS','010');			
			f.cmbcodloc.options[11]= new Option('MILLA','011');			
			f.cmbcodloc.options[12]= new Option('OSUNA RODRIGUEZ','012');			
			f.cmbcodloc.options[13]= new Option('SAGRARIO','013');			
			f.cmbcodloc.options[14]= new Option('EL MORRO','014');			
			f.cmbcodloc.options[15]= new Option('LOS NEVADOS','015');			
		}
		if(f.cmbcodmun.value=="013")//  MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('MIRANDA','001');			
			f.cmbcodloc.options[2]= new Option('ANDRES ELOY BLANCO','002');			
			f.cmbcodloc.options[3]= new Option('LA VENTA','003');			
			f.cmbcodloc.options[4]= new Option('PIÑANGO','004');			
		}
		if(f.cmbcodmun.value=="014")//  OBISPO RAMOS DE LORA
		{
			f.cmbcodloc.options[1]= new Option('OBISPO RAMOS DE LORA','001');			
			f.cmbcodloc.options[2]= new Option('ELOY PAREDES','002');			
			f.cmbcodloc.options[3]= new Option('SAN RAFAEL DE ALCAZAR','003');			
		}
		if(f.cmbcodmun.value=="015")//  PADRE NOGUERA
		{
		}
		if(f.cmbcodmun.value=="016")//  PUEBLO LLANO
		{
		}
		if(f.cmbcodmun.value=="017")//  RANGEL
		{
			f.cmbcodloc.options[1]= new Option('RANGEL','001');			
			f.cmbcodloc.options[2]= new Option('CACUTE','002');			
			f.cmbcodloc.options[3]= new Option('LA TOMA','003');			
			f.cmbcodloc.options[4]= new Option('MUCURUBA','004');			
			f.cmbcodloc.options[5]= new Option('SAN RAFAEL','005');			
		}
		if(f.cmbcodmun.value=="018")//  RIVAS DÁVILA
		{
			f.cmbcodloc.options[1]= new Option('RIVAS DAVILA','001');			
			f.cmbcodloc.options[2]= new Option('GERONIMO MALDONADO','002');			
		}
		if(f.cmbcodmun.value=="019")//  SANTOS MARQUINA
		{
		}
		if(f.cmbcodmun.value=="020")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('CHIGUARA','002');			
			f.cmbcodloc.options[3]= new Option('ESTANQUES','003');			
			f.cmbcodloc.options[4]= new Option('LA TRAMPA','004');			
			f.cmbcodloc.options[5]= new Option('PUEBLO NUEVO DEL SUR','005');			
			f.cmbcodloc.options[6]= new Option('SAN JUAN','006');			
		}
		if(f.cmbcodmun.value=="021")//  TOVAR
		{
			f.cmbcodloc.options[1]= new Option('EL AMPARO','001');			
			f.cmbcodloc.options[2]= new Option('EL LLANO','002');			
			f.cmbcodloc.options[3]= new Option('SAN FRANCISCO','003');			
			f.cmbcodloc.options[4]= new Option('TOVAR','004');			
		}
		if(f.cmbcodmun.value=="022")//  TULIO FEBRES CORDERO
		{
			f.cmbcodloc.options[1]= new Option('TULIO FEBRES CORDERO','001');			
			f.cmbcodloc.options[2]= new Option('INDEPENDENCIA','002');			
			f.cmbcodloc.options[3]= new Option('MARIA DE LA CONCEPCION PALACIOS BLANCO','003');			
			f.cmbcodloc.options[4]= new Option('SANTA POLONIA','004');			
		}
		if(f.cmbcodmun.value=="023")//  ZEA
		{
			f.cmbcodloc.options[1]= new Option('ZEA','001');			
			f.cmbcodloc.options[2]= new Option('CAÑO EL TIGRE','002');			
		}
	}
	if(f.cmbcodent.value=="15")// MIRANDA
	{
		if(f.cmbcodmun.value=="001")//  ACEVEDO
		{
			f.cmbcodloc.options[1]= new Option('CAUCAGUA','001');			
			f.cmbcodloc.options[2]= new Option('ARAGUITA','002');			
			f.cmbcodloc.options[3]= new Option('AREVALO GONZALEZ','003');			
			f.cmbcodloc.options[4]= new Option('CAPAYA','004');			
			f.cmbcodloc.options[5]= new Option('EL CAFE','005');			
			f.cmbcodloc.options[6]= new Option('MARIZAPA','006');			
			f.cmbcodloc.options[7]= new Option('PANAQUIRE','007');			
			f.cmbcodloc.options[8]= new Option('RIBAS','008');			
		}
		if(f.cmbcodmun.value=="002")//  ANDRÉS BELLO
		{
			f.cmbcodloc.options[1]= new Option('SAN JOSE DE BARLOVENTO','001');			
			f.cmbcodloc.options[2]= new Option('CUMBO','002');			
		}
		if(f.cmbcodmun.value=="003")//  BARUTA
		{
			f.cmbcodloc.options[1]= new Option('BARUTA','001');			
			f.cmbcodloc.options[2]= new Option('EL CAFETAL','002');			
			f.cmbcodloc.options[3]= new Option('LAS MINAS DE BARUTA','003');			
		}
		if(f.cmbcodmun.value=="004")//  BRIÓN
		{
			f.cmbcodloc.options[1]= new Option('HIGUEROTE','001');			
			f.cmbcodloc.options[2]= new Option('CURIEPE','002');			
			f.cmbcodloc.options[3]= new Option('TACARIGUA','003');			
		}
		if(f.cmbcodmun.value=="005")//  BUROZ
		{
			f.cmbcodloc.options[1]= new Option('MAMPORAL','001');			
		}
		if(f.cmbcodmun.value=="006")//  CARRIZAL
		{
			f.cmbcodloc.options[1]= new Option('CARRIZAL','001');			
		}
		if(f.cmbcodmun.value=="007")//  CHACAO
		{
			f.cmbcodloc.options[1]= new Option('CHACAO','001');			
		}
		if(f.cmbcodmun.value=="008")//  CRISTÓBAL ROJAS
		{
			f.cmbcodloc.options[1]= new Option('CHARALLAVE','001');			
			f.cmbcodloc.options[2]= new Option('LAS BRISAS','002');			
		}
		if(f.cmbcodmun.value=="009")//  EL HATILLO
		{
			f.cmbcodloc.options[1]= new Option('EL HATILLO','001');			
		}
		if(f.cmbcodmun.value=="010")//  GUAICAIPURO
		{
			f.cmbcodloc.options[1]= new Option('LOS TEQUES','001');			
			f.cmbcodloc.options[2]= new Option('ALTAGRACIA DE LA MONTAÑA','002');			
			f.cmbcodloc.options[3]= new Option('CECILIO ACOSTA','003');			
			f.cmbcodloc.options[4]= new Option('EL JARILLO','004');			
			f.cmbcodloc.options[5]= new Option('PARACOTOS','005');			
			f.cmbcodloc.options[6]= new Option('SAN PEDRO','006');			
			f.cmbcodloc.options[7]= new Option('TACACTA','007');			
		}
		if(f.cmbcodmun.value=="011")//  INDEPENDENCIA
		{
			f.cmbcodloc.options[1]= new Option('SANTA TERESA DEL TUY','001');			
			f.cmbcodloc.options[2]= new Option('EL CARTANAL','002');			
		}
		if(f.cmbcodmun.value=="012")//  LANDER
		{
			f.cmbcodloc.options[1]= new Option('OCUMARE DEL TUY','001');			
			f.cmbcodloc.options[2]= new Option('LA DEMOCRACIA','002');			
			f.cmbcodloc.options[3]= new Option('SANTA BARBARA','003');			
		}
		if(f.cmbcodmun.value=="013")//  LOS SALIAS
		{
			f.cmbcodloc.options[1]= new Option('SAN ANTONIO DE LOS ALTOS','001');			
		}
		if(f.cmbcodmun.value=="014")//  PÁEZ
		{
			f.cmbcodloc.options[1]= new Option('RIO CHICO','001');			
			f.cmbcodloc.options[2]= new Option('EL GUAPO','002');			
			f.cmbcodloc.options[3]= new Option('TACARIGUA DE LA LAGUNA','003');			
			f.cmbcodloc.options[4]= new Option('PAPARO','004');			
			f.cmbcodloc.options[5]= new Option('SAN FERNANDO DEL GUAPO','005');			
		}
		if(f.cmbcodmun.value=="015")//  PAZ CASTILLO
		{
			f.cmbcodloc.options[1]= new Option('SANTA LUCIA','001');			
		}
		if(f.cmbcodmun.value=="016")//  PEDRO GUAL
		{
			f.cmbcodloc.options[1]= new Option('CUPIRA','001');			
			f.cmbcodloc.options[2]= new Option('MACHURUCUTO','002');			
		}
		if(f.cmbcodmun.value=="017")//  PLAZA
		{
			f.cmbcodloc.options[1]= new Option('GUARENAS','001');			
		}
		if(f.cmbcodmun.value=="018")//  SIMÓN BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('SAN FRANCISCO DE YARE','001');			
			f.cmbcodloc.options[2]= new Option('SAN ANTONIO DE YARE','002');			
		}
		if(f.cmbcodmun.value=="019")// SUCRE
		{
			f.cmbcodloc.options[1]= new Option('PETARE','001');			
			f.cmbcodloc.options[2]= new Option('CAUCAGUITA','002');			
			f.cmbcodloc.options[3]= new Option('FILAS DE MARICHES','003');			
			f.cmbcodloc.options[4]= new Option('LA DOLORITA','004');			
			f.cmbcodloc.options[5]= new Option('LEONCIO MARTINEZ','005');			
		}
		if(f.cmbcodmun.value=="020")//  URDANETA
		{
			f.cmbcodloc.options[1]= new Option('CUA','001');			
			f.cmbcodloc.options[2]= new Option('NUEVA CUA','002');			
		}
		if(f.cmbcodmun.value=="021")// ZAMORA
		{
			f.cmbcodloc.options[1]= new Option('GUATIRE','001');			
			f.cmbcodloc.options[2]= new Option('BOLIVAR','002');			
		}
	}
	if(f.cmbcodent.value=="16")// MONAGAS
	{
		if(f.cmbcodmun.value=="001")//  ACOSTA
		{
			f.cmbcodloc.options[1]= new Option('ACOSTA','001');			
			f.cmbcodloc.options[2]= new Option('SAN FRANCISCO','002');			
		}
		if(f.cmbcodmun.value=="002")//  AGUASAY
		{
		}
		if(f.cmbcodmun.value=="003")//  BOLÍVAR
		{
		}
		if(f.cmbcodmun.value=="004")//  CARIPE
		{
			f.cmbcodloc.options[1]= new Option('CARIPE','001');			
			f.cmbcodloc.options[2]= new Option('EL GUACHARO','002');			
			f.cmbcodloc.options[3]= new Option('LA GUANOTA','003');			
			f.cmbcodloc.options[4]= new Option('SABANA DE PIEDRA','004');			
			f.cmbcodloc.options[5]= new Option('SAN AGUSTIN','005');			
			f.cmbcodloc.options[6]= new Option('TERESEN','006');			
		}
		if(f.cmbcodmun.value=="005")//  CEDEÑO
		{
			f.cmbcodloc.options[1]= new Option('CEDEÑO','001');			
			f.cmbcodloc.options[2]= new Option('AREO','002');			
			f.cmbcodloc.options[3]= new Option('SAN FELIX','003');			
			f.cmbcodloc.options[4]= new Option('VIENTO FRESCO','004');			
		}
		if(f.cmbcodmun.value=="006")//  EZEQUIEL ZAMORA
		{
			f.cmbcodloc.options[1]= new Option('EZEQUIEL ZAMORA','001');			
			f.cmbcodloc.options[2]= new Option('EL TEJERO','002');			
		}
		if(f.cmbcodmun.value=="007")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('LIBERTADOR','001');			
			f.cmbcodloc.options[2]= new Option('CHAGUARAMAS','002');			
			f.cmbcodloc.options[3]= new Option('LAS ALHUAACAS','003');			
			f.cmbcodloc.options[4]= new Option('TABASCA','004');			
		}
		if(f.cmbcodmun.value=="008")//  MATURIN
		{
			f.cmbcodloc.options[1]= new Option('MATURIN','001');			
			f.cmbcodloc.options[2]= new Option('ALTO DE LOS GODOS','002');			
			f.cmbcodloc.options[3]= new Option('BOQUERON','003');			
			f.cmbcodloc.options[4]= new Option('LAS COCUIZAS','004');			
			f.cmbcodloc.options[5]= new Option('SAN SIMON','005');			
			f.cmbcodloc.options[6]= new Option('SANTA CRUZ','006');			
			f.cmbcodloc.options[7]= new Option('EL COROZO','007');			
			f.cmbcodloc.options[8]= new Option('EL FURRIAL','008');			
			f.cmbcodloc.options[9]= new Option('JUSEPIN','009');			
			f.cmbcodloc.options[10]= new Option('LA PICA','010');			
			f.cmbcodloc.options[11]= new Option('SAN VICENTE','011');			
		}
		if(f.cmbcodmun.value=="009")//  PIAR
		{
			f.cmbcodloc.options[1]= new Option('PIAR','001');			
			f.cmbcodloc.options[2]= new Option('APARICIO','002');			
			f.cmbcodloc.options[3]= new Option('CHAGUARAMAL','003');			
			f.cmbcodloc.options[4]= new Option('EL PINTO','004');			
			f.cmbcodloc.options[5]= new Option('GUANAGUANA','005');			
			f.cmbcodloc.options[6]= new Option('LA TOSCANA','006');			
			f.cmbcodloc.options[7]= new Option('TAGUAYA','007');			
		}
		if(f.cmbcodmun.value=="010")//  PUNCERES
		{
			f.cmbcodloc.options[1]= new Option('PUNCERES','001');			
			f.cmbcodloc.options[2]= new Option('CACHIPO','002');			
		}
		if(f.cmbcodmun.value=="011")//  SANTA BÁRBARA
		{
		}
		if(f.cmbcodmun.value=="012")//  SOTILLO
		{
			f.cmbcodloc.options[1]= new Option('SOTILLO','001');			
			f.cmbcodloc.options[2]= new Option('LOS BARRANCOS DE FAJARDO','002');			
		}
		if(f.cmbcodmun.value=="013")//  URACOA
		{
		}
	}
	if(f.cmbcodent.value=="17")// NUEVA ESPARTA
	{
		if(f.cmbcodmun.value=="001")//  ANTOLÍN DEL CAMPO
		{
		}
		if(f.cmbcodmun.value=="002")//  ARISMENDI
		{
		}
		if(f.cmbcodmun.value=="003")//  DÍAZ
		{
			f.cmbcodloc.options[1]= new Option('DIAZ','001');			
			f.cmbcodloc.options[2]= new Option('ZABALA','002');			
		}
		if(f.cmbcodmun.value=="004")//  GARCÍA
		{
			f.cmbcodloc.options[1]= new Option('GARCIA','001');			
			f.cmbcodloc.options[2]= new Option('FRANCISCO FAJARDO','002');			
		}
		if(f.cmbcodmun.value=="005")//  GÓMEZ
		{
			f.cmbcodloc.options[1]= new Option('GOMEZ','001');			
			f.cmbcodloc.options[2]= new Option('BOLIVAR','002');			
			f.cmbcodloc.options[3]= new Option('GUEVARA','003');			
			f.cmbcodloc.options[4]= new Option('MATASIETE','004');			
			f.cmbcodloc.options[5]= new Option('SUCRE','005');			
		}
		if(f.cmbcodmun.value=="006")//  MANEIRO
		{
			f.cmbcodloc.options[1]= new Option('MANEIRO','001');			
			f.cmbcodloc.options[2]= new Option('AGUIRRE','002');			
		}
		if(f.cmbcodmun.value=="007")//  MARCANO
		{
			f.cmbcodloc.options[1]= new Option('MARCANO','001');			
			f.cmbcodloc.options[2]= new Option('ADRIAN','002');			
		}
		if(f.cmbcodmun.value=="008")//  MARIÑO
		{
		}
		if(f.cmbcodmun.value=="009")//  PENÍNSULA DE MACANAO
		{
			f.cmbcodloc.options[1]= new Option('PENINSULA DE MACANAO','001');			
			f.cmbcodloc.options[2]= new Option('SAN FRANCISCO','002');			
		}
		if(f.cmbcodmun.value=="010")//  TUBORES
		{
			f.cmbcodloc.options[1]= new Option('TUBORES','001');			
			f.cmbcodloc.options[2]= new Option('LOS BARALES','002');			
		}
		if(f.cmbcodmun.value=="011")//  VILLALBA
		{
			f.cmbcodloc.options[1]= new Option('VILLALBA','001');			
			f.cmbcodloc.options[2]= new Option('VICENTE FUENTES','002');			
		}
	}
	if(f.cmbcodent.value=="18")// PORTUGUESA
	{
		if(f.cmbcodmun.value=="001")//  AGUA BLANCA
		{
		}
		if(f.cmbcodmun.value=="002")//  ARAURE
		{
			f.cmbcodloc.options[1]= new Option('ARAURE','001');			
			f.cmbcodloc.options[2]= new Option('RIO ACARIGUA','002');			
		}
		if(f.cmbcodmun.value=="003")//  ESTELLER
		{
			f.cmbcodloc.options[1]= new Option('ESTELLER','001');			
			f.cmbcodloc.options[2]= new Option('UVERAL','002');			
		}
		if(f.cmbcodmun.value=="004")//  GUANARE
		{
			f.cmbcodloc.options[1]= new Option('GUANARE','001');			
			f.cmbcodloc.options[2]= new Option('CORDOBA','002');			
			f.cmbcodloc.options[3]= new Option('SAN JOSE DE LA MONTAÑA','003');			
			f.cmbcodloc.options[4]= new Option('SAN JUAN DE GUANAGUANARE','004');			
			f.cmbcodloc.options[5]= new Option('VIRGEN DE LA COROMOTO','005');			
		}
		if(f.cmbcodmun.value=="005")//  GUANARITO
		{
			f.cmbcodloc.options[1]= new Option('GUANARITO','001');			
			f.cmbcodloc.options[2]= new Option('TRINIDAD DE LA CAPILLA','002');			
			f.cmbcodloc.options[3]= new Option('DIVINA PASTORA','003');			
		}
		if(f.cmbcodmun.value=="006")//  MONSEÑOR JOSÉ VICENTE DE UNDA
		{
			f.cmbcodloc.options[1]= new Option('MONSEÑOR JOSÉ VICENTE DE UNDA','001');			
			f.cmbcodloc.options[2]= new Option('PEÑA BLANCA','002');			
		}
		if(f.cmbcodmun.value=="007")//  OSPINO
		{
			f.cmbcodloc.options[1]= new Option('OSPINO','001');			
			f.cmbcodloc.options[2]= new Option('APARICION','002');			
			f.cmbcodloc.options[3]= new Option('LA ESTACION','003');			
		}
		if(f.cmbcodmun.value=="008")//  PÁEZ
		{
			f.cmbcodloc.options[1]= new Option('PAEZ','001');			
			f.cmbcodloc.options[2]= new Option('PAYARA','002');			
			f.cmbcodloc.options[3]= new Option('PIMPINELA','003');			
			f.cmbcodloc.options[4]= new Option('RAMON PERAZA','004');			
		}
		if(f.cmbcodmun.value=="009")//  PAPELÓN
		{
			f.cmbcodloc.options[1]= new Option('PAPELON','001');			
			f.cmbcodloc.options[2]= new Option('CAÑO DELGADITO','002');			
		}
		if(f.cmbcodmun.value=="010")//  SAN GENARO DE BOCONOITO
		{
			f.cmbcodloc.options[1]= new Option('SAN GENARO DE BOCONOITO','001');			
			f.cmbcodloc.options[2]= new Option('ANTOLIN TOVAR','002');			
		}
		if(f.cmbcodmun.value=="011")//  SAN RAFAEL DE ONOTO
		{
			f.cmbcodloc.options[1]= new Option('SAN RAFAEL DE ONOTO','001');			
			f.cmbcodloc.options[2]= new Option('SANTA FE','002');			
			f.cmbcodloc.options[3]= new Option('THERMO MORLES','003');			
		}
		if(f.cmbcodmun.value=="012")//  SANTA ROSALÍA
		{
			f.cmbcodloc.options[1]= new Option('SANTA ROSALIA','001');			
			f.cmbcodloc.options[2]= new Option('FLORIDA','002');			
		}
		if(f.cmbcodmun.value=="013")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('CONCEPCION','002');			
			f.cmbcodloc.options[3]= new Option('SAN RAFAEL DE PALO ALZAO','003');			
			f.cmbcodloc.options[4]= new Option('UVENCIO ANTONIO VELASQUEZ','004');			
			f.cmbcodloc.options[5]= new Option('SAN JOSE DE SAGUAZ','005');			
			f.cmbcodloc.options[6]= new Option('VILLA ROSA','006');			
		}
		if(f.cmbcodmun.value=="014")//  TURÉN
		{
			f.cmbcodloc.options[1]= new Option('TUREN','001');			
			f.cmbcodloc.options[2]= new Option('CANELONES','002');			
			f.cmbcodloc.options[3]= new Option('SANTA CRUZ','003');			
			f.cmbcodloc.options[4]= new Option('SAN ISIDRO LABRADOR','004');			
		}
	}
	if(f.cmbcodent.value=="19")// SUCRE
	{
		if(f.cmbcodmun.value=="001")//  ANDRÉS ELOY BLANCO
		{
			f.cmbcodloc.options[1]= new Option('MARIÑO','001');			
			f.cmbcodloc.options[2]= new Option('ROMULO GALLEGOS','002');			
		}
		if(f.cmbcodmun.value=="002")//  ANDRÉS MATA
		{
			f.cmbcodloc.options[1]= new Option('SAN JOSE DE AEROCUAR','001');			
			f.cmbcodloc.options[2]= new Option('TAVERA ACOSTA','002');			
		}
		if(f.cmbcodmun.value=="003")//  ARISMENDI
		{
			f.cmbcodloc.options[1]= new Option('RIO CARIBE','001');			
			f.cmbcodloc.options[2]= new Option('ANTONIO JOSE DE SUCRE','002');			
			f.cmbcodloc.options[3]= new Option('EL MORRO DE PUERTO SANTO','003');			
			f.cmbcodloc.options[4]= new Option('PUERTO SANTO','004');			
			f.cmbcodloc.options[5]= new Option('SAN JUAN DE LAS GALDONAS','005');			
		}
		if(f.cmbcodmun.value=="004")//  BENÍTEZ
		{
			f.cmbcodloc.options[1]= new Option('EL PILAR','001');			
			f.cmbcodloc.options[2]= new Option('EL RINCON','002');			
			f.cmbcodloc.options[3]= new Option('GRAL FRANCISCO ANTONIO VASQUEZ','003');			
			f.cmbcodloc.options[4]= new Option('GUARAUNOS','004');			
			f.cmbcodloc.options[5]= new Option('TUNAPUICITO','005');			
			f.cmbcodloc.options[6]= new Option('UNION','006');			
		}
		if(f.cmbcodmun.value=="005")//  BERMÚDEZ
		{
			f.cmbcodloc.options[1]= new Option('BOLIVAR','001');			
			f.cmbcodloc.options[2]= new Option('MACARAPANA','002');			
			f.cmbcodloc.options[3]= new Option('SANTA CATALINA','003');			
			f.cmbcodloc.options[4]= new Option('SANTA ROSA','004');			
			f.cmbcodloc.options[5]= new Option('SANTA TERESA','005');			
		}
		if(f.cmbcodmun.value=="006")//  BOLÍVAR
		{
		}
		if(f.cmbcodmun.value=="007")//  CAJIGAL
		{
			f.cmbcodloc.options[1]= new Option('YAGUARAPARO','001');			
			f.cmbcodloc.options[2]= new Option('EL PAUJIL','002');			
			f.cmbcodloc.options[3]= new Option('LIBERTAD','003');			
		}
		if(f.cmbcodmun.value=="008")//  CRUZ SALMERÓN ACOSTA
		{
			f.cmbcodloc.options[1]= new Option('AYARA','001');			
			f.cmbcodloc.options[2]= new Option('CHACOPACA','002');			
			f.cmbcodloc.options[3]= new Option('MANICUARE','003');			
		}
		if(f.cmbcodmun.value=="009")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('TUNAPUY','001');			
			f.cmbcodloc.options[2]= new Option('CAMPO ELIAS','002');			
		}
		if(f.cmbcodmun.value=="010")//  MARIÑO
		{
			f.cmbcodloc.options[1]= new Option('IRAPA','001');			
			f.cmbcodloc.options[2]= new Option('CAMPO CLARO','002');			
			f.cmbcodloc.options[3]= new Option('MARABAL','003');			
			f.cmbcodloc.options[4]= new Option('SAN ANTONIO DE IRAPA','004');			
			f.cmbcodloc.options[5]= new Option('SORO','005');			
		}
		if(f.cmbcodmun.value=="011")//  MEJÍA
		{
		}
		if(f.cmbcodmun.value=="012")//  MONTES
		{
			f.cmbcodloc.options[1]= new Option('CUMANACOA','001');			
			f.cmbcodloc.options[2]= new Option('ARENAS','002');			
			f.cmbcodloc.options[3]= new Option('ARICAGUA','003');			
			f.cmbcodloc.options[4]= new Option('COCOLLAR','004');			
			f.cmbcodloc.options[5]= new Option('SAN FERNANDO','005');			
			f.cmbcodloc.options[6]= new Option('SAN LORENZO','006');			
		}
		if(f.cmbcodmun.value=="013")//  RIBERO
		{
			f.cmbcodloc.options[1]= new Option('CARIACO','001');			
			f.cmbcodloc.options[2]= new Option('CATUARO','002');			
			f.cmbcodloc.options[3]= new Option('RENDON','003');			
			f.cmbcodloc.options[4]= new Option('SANTA CRUZ','004');			
			f.cmbcodloc.options[5]= new Option('SANTA MARIA','005');			
		}
		if(f.cmbcodmun.value=="014")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('ALTAGRACIA','001');			
			f.cmbcodloc.options[2]= new Option('AYACUCHO','002');			
			f.cmbcodloc.options[3]= new Option('SANTA INES','003');			
			f.cmbcodloc.options[4]= new Option('VALENTIN VALIENTE','004');			
			f.cmbcodloc.options[5]= new Option('SAN JUAN','005');			
			f.cmbcodloc.options[6]= new Option('RAUL LEONI','006');			
			f.cmbcodloc.options[7]= new Option('SANTA FE','007');			
		}
		if(f.cmbcodmun.value=="015")//  VALDEZ
		{
			f.cmbcodloc.options[1]= new Option('GUIRIA','001');			
			f.cmbcodloc.options[2]= new Option('BIDEAU','002');			
			f.cmbcodloc.options[3]= new Option('CRISTOBAL COLON','003');			
			f.cmbcodloc.options[4]= new Option('PUNTA DE PIEDRA','004');			
		}
	}
	if(f.cmbcodent.value=="20")// TACHIRA
	{
		if(f.cmbcodmun.value=="001")//  ANDRÉS BELLO
		{
		}
		if(f.cmbcodmun.value=="002")//  ANTONIO RÓMULO COSTA
		{
		}
		if(f.cmbcodmun.value=="003")//  AYACUCHO
		{
			f.cmbcodloc.options[1]= new Option('AYACUYO','001');			
			f.cmbcodloc.options[2]= new Option('RIVAS BERTI','002');			
			f.cmbcodloc.options[3]= new Option('SAN PEDRO DEL RIO','003');			
		}
		if(f.cmbcodmun.value=="004")//  BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('BOLIVAR','001');			
			f.cmbcodloc.options[2]= new Option('PALOTAL','002');			
			f.cmbcodloc.options[3]= new Option('GENERAL JUAN VICENTE GOMEZ','003');			
			f.cmbcodloc.options[4]= new Option('ISAIAS MEDINA ANGARITA','004');			
		}
		if(f.cmbcodmun.value=="005")//  CÁRDENAS
		{
			f.cmbcodloc.options[1]= new Option('CARDENAS','001');			
			f.cmbcodloc.options[2]= new Option('AMENODORO RANGEL LAMUS','002');			
			f.cmbcodloc.options[3]= new Option('LA FLORIDA','003');			
		}
		if(f.cmbcodmun.value=="006")//  CÓRDOBA
		{
		}
		if(f.cmbcodmun.value=="007")//  FERNÁNDEZ FEO
		{
			f.cmbcodloc.options[1]= new Option('FERNANDEZ FEO','001');			
			f.cmbcodloc.options[2]= new Option('ALBERTO ADRIANI','002');			
			f.cmbcodloc.options[3]= new Option('SANTO DOMINGO','003');			
		}
		if(f.cmbcodmun.value=="008")//  FRANCISCO DE MIRANDA
		{
		}
		if(f.cmbcodmun.value=="009")//  GARCÍA DE HEVIA
		{
			f.cmbcodloc.options[1]= new Option('GARCÍA DE HEVIA','001');			
			f.cmbcodloc.options[2]= new Option('BOCA DE GRITA','002');			
			f.cmbcodloc.options[3]= new Option('JOSE ANTONIO PAEZ','003');			
		}
		if(f.cmbcodmun.value=="010")//  GUÁSIMOS
		{
		}
		if(f.cmbcodmun.value=="011")//  INDEPENDENCIA
		{
			f.cmbcodloc.options[1]= new Option('INDEPENDENCIA','001');			
			f.cmbcodloc.options[2]= new Option('JUAN GERMAN ROSCIO','002');			
			f.cmbcodloc.options[3]= new Option('ROMAN CARDENAS','003');			
		}
		if(f.cmbcodmun.value=="012")//  JÁUREGUI
		{
			f.cmbcodloc.options[1]= new Option('JÁUREGUI','001');			
			f.cmbcodloc.options[2]= new Option('EMILIO CONSTANTINO GUERRERO','002');			
			f.cmbcodloc.options[3]= new Option('MONSEÑOR MIGUEL ANTONIO SALAS','003');			
		}
		if(f.cmbcodmun.value=="013")//  JOSÉ MARÍA VARGAS
		{
		}
		if(f.cmbcodmun.value=="014")//  JUNÍN
		{
			f.cmbcodloc.options[1]= new Option('JUNIN','001');			
			f.cmbcodloc.options[2]= new Option('LA PETROLEA','002');			
			f.cmbcodloc.options[3]= new Option('QUINIMARI','003');			
			f.cmbcodloc.options[4]= new Option('BRAMON','004');			
		}
		if(f.cmbcodmun.value=="015")//  LIBERTAD
		{
			f.cmbcodloc.options[1]= new Option('LIBERTAD','001');			
			f.cmbcodloc.options[2]= new Option('CIPRIANO CASTRO','002');			
			f.cmbcodloc.options[3]= new Option('MANUEL FELIPE RUGELES','003');			
		}
		if(f.cmbcodmun.value=="016")//  LIBERTADOR
		{
			f.cmbcodloc.options[1]= new Option('LIBERTADOR','001');			
			f.cmbcodloc.options[2]= new Option('EMETERIO OCHOA','002');			
			f.cmbcodloc.options[3]= new Option('DORADAS','003');			
			f.cmbcodloc.options[4]= new Option('SAN JOAQUIN DE NAVAY','004');			
		}
		if(f.cmbcodmun.value=="017")//  LOBATERA
		{
			f.cmbcodloc.options[1]= new Option('LOBATERA','001');			
			f.cmbcodloc.options[2]= new Option('CONSTITUCION','002');			
		}
		if(f.cmbcodmun.value=="018")//  MICHELENA
		{
		}
		if(f.cmbcodmun.value=="019")//  PANAMERICANO
		{
			f.cmbcodloc.options[1]= new Option('PANAMERICANO','001');			
			f.cmbcodloc.options[2]= new Option('LA PALMITA','002');			
		}
		if(f.cmbcodmun.value=="020")//  PEDRO MARÍA UREÑA
		{
			f.cmbcodloc.options[1]= new Option('PEDRO MARIA OREÑA','001');			
			f.cmbcodloc.options[2]= new Option('NUEVA ARCADIA','002');			
		}
		if(f.cmbcodmun.value=="021")//  RAFAEL URDANETA
		{
		}
		if(f.cmbcodmun.value=="022")//  SAMUEL DARÍO MALDONADO
		{
			f.cmbcodloc.options[1]= new Option('SAMUEL DARIO MALDONADO','001');			
			f.cmbcodloc.options[2]= new Option('BOCONO','002');			
			f.cmbcodloc.options[3]= new Option('HERNANDEZ','003');			
		}
		if(f.cmbcodmun.value=="023")//  SAN CRISTÓBAL
		{
			f.cmbcodloc.options[1]= new Option('LA CONCORDIA','001');			
			f.cmbcodloc.options[2]= new Option('PEDRO MARIA MORANTES','002');			
			f.cmbcodloc.options[3]= new Option('SAN JUAN BAUTISTA','003');			
			f.cmbcodloc.options[4]= new Option('SAN SEBASTIAN','004');			
			f.cmbcodloc.options[5]= new Option('DR FRANCISCO ROMERO LOBO','005');			
		}
		if(f.cmbcodmun.value=="024")//  SEBORUCO
		{
		}
		if(f.cmbcodmun.value=="025")//  SIMÓN RODRÍGUEZ
		{
		}
		if(f.cmbcodmun.value=="026")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('MUNICIPIO CAPITAL SUCRE','001');			
			f.cmbcodloc.options[2]= new Option('ELEAZAR LOPEZ CONTRERAS','002');			
			f.cmbcodloc.options[3]= new Option('SAN PABLO','003');			
		}
		if(f.cmbcodmun.value=="027")//  TORBES
		{
		}
		if(f.cmbcodmun.value=="028")// URIBANTE
		{
			f.cmbcodloc.options[1]= new Option('MUNICIPIO CAPITAL URIBANTE','001');			
			f.cmbcodloc.options[2]= new Option('CARDENAS','002');			
			f.cmbcodloc.options[3]= new Option('JUAN PABLO PEÑALOZA','003');			
			f.cmbcodloc.options[4]= new Option('POTOSI','004');			
		}
		if(f.cmbcodmun.value=="029")//  SAN JUDAS TADEO
		{
		}
	}
	if(f.cmbcodent.value=="21")// TRUJILLO
	{
		if(f.cmbcodmun.value=="001")//  ANDRÉS BELLO
		{
			f.cmbcodloc.options[1]= new Option('SANTA ISABEL','001');			
			f.cmbcodloc.options[2]= new Option('EL ARAGUANEY','002');			
			f.cmbcodloc.options[3]= new Option('EL JAGUITO','003');			
			f.cmbcodloc.options[4]= new Option('LA ESPERANZA','004');			
		}
		if(f.cmbcodmun.value=="002")//  BOCONÓ
		{
			f.cmbcodloc.options[1]= new Option('BOCONO','001');			
			f.cmbcodloc.options[2]= new Option('EL CARMEN','002');			
			f.cmbcodloc.options[3]= new Option('MOSQUEY','003');			
			f.cmbcodloc.options[4]= new Option('AYACUCHO','004');			
			f.cmbcodloc.options[5]= new Option('BURBUSAY','005');			
			f.cmbcodloc.options[6]= new Option('GENERAL RIVAS','006');			
			f.cmbcodloc.options[7]= new Option('GUARAMACAL','007');			
			f.cmbcodloc.options[8]= new Option('LA VEGA DE GUARAMACAL','008');			
			f.cmbcodloc.options[9]= new Option('MONSEÑOR JAUREGUI','009');			
			f.cmbcodloc.options[10]= new Option('RAFAEL RANGEL','010');			
			f.cmbcodloc.options[11]= new Option('SAN MIGUEL','011');			
			f.cmbcodloc.options[12]= new Option('SAN JOSE','012');			
		}
		if(f.cmbcodmun.value=="003")//  BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('SABANA GRANDE','001');			
			f.cmbcodloc.options[2]= new Option('CHEREGUE','002');			
			f.cmbcodloc.options[3]= new Option('GRANADOS','003');			
		}
		if(f.cmbcodmun.value=="004")//  CANDELARIA
		{
			f.cmbcodloc.options[1]= new Option('CHEJENDE','001');			
			f.cmbcodloc.options[2]= new Option('ARNOLDO GABALDON','002');			
			f.cmbcodloc.options[3]= new Option('BOLIVIA','003');			
			f.cmbcodloc.options[4]= new Option('CARRILLO','004');			
			f.cmbcodloc.options[5]= new Option('CEGARRA','005');			
			f.cmbcodloc.options[6]= new Option('MANUEL SALVADOR ULLOA','006');			
			f.cmbcodloc.options[7]= new Option('SAN JOSE','007');			
		}
		if(f.cmbcodmun.value=="005")//  CARACHE
		{
			f.cmbcodloc.options[1]= new Option('CARACHE','001');			
			f.cmbcodloc.options[2]= new Option('CUICAS','002');			
			f.cmbcodloc.options[3]= new Option('LA CONCEPCION','003');			
			f.cmbcodloc.options[4]= new Option('PANAMERICANA','004');			
			f.cmbcodloc.options[5]= new Option('SANTA CRUZ','005');			
		}
		if(f.cmbcodmun.value=="006")//  ESCUQUE
		{
			f.cmbcodloc.options[1]= new Option('ESCUQUE','001');			
			f.cmbcodloc.options[2]= new Option('LA UNION','002');			
			f.cmbcodloc.options[3]= new Option('SABANA LIBRE','003');			
			f.cmbcodloc.options[4]= new Option('SANTA RITA','004');			
		}
		if(f.cmbcodmun.value=="007")//  JOSÉ FELIPE MÁRQUEZ CAÑIZALES
		{
			f.cmbcodloc.options[1]= new Option('EL SOCORRO','001');			
			f.cmbcodloc.options[2]= new Option('ANTONIO JOSE DE SUCRE','002');			
			f.cmbcodloc.options[3]= new Option('LOS CAPRICHOS','003');			
		}
		if(f.cmbcodmun.value=="008")//  JOSÉ VICENTE CAMPO ELÍAS
		{
			f.cmbcodloc.options[1]= new Option('CAMPO ELIAS','001');			
			f.cmbcodloc.options[2]= new Option('ARNOLDO GABALDON','002');			
		}
		if(f.cmbcodmun.value=="009")//  LA CEIBA
		{
			f.cmbcodloc.options[1]= new Option('SANTA APOLONIA','001');			
			f.cmbcodloc.options[2]= new Option('EL PROGRESO','002');			
			f.cmbcodloc.options[3]= new Option('LA CEIBA','003');			
			f.cmbcodloc.options[4]= new Option('TRES DE FEBRERO','004');			
		}
		if(f.cmbcodmun.value=="010")//  MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('EL DIVIDIVE','001');			
			f.cmbcodloc.options[2]= new Option('AGUA SANTA','002');			
			f.cmbcodloc.options[3]= new Option('AGUA CALIENTE','003');			
			f.cmbcodloc.options[4]= new Option('EL CENIZO','004');			
			f.cmbcodloc.options[5]= new Option('VALERITA','005');			
		}
		if(f.cmbcodmun.value=="011")// MONTE CARMELO
		{
			f.cmbcodloc.options[1]= new Option('MONTE CARMELO','001');			
			f.cmbcodloc.options[2]= new Option('BUENA VISTA','002');			
			f.cmbcodloc.options[3]= new Option('SANTA MARIA DEL HORCON','003');			
		}
		if(f.cmbcodmun.value=="012")//  MOTATÁN
		{
			f.cmbcodloc.options[1]= new Option('MOTATAN','001');			
			f.cmbcodloc.options[2]= new Option('EL BAÑO','002');			
			f.cmbcodloc.options[3]= new Option('JALISCO','003');			
		}
		if(f.cmbcodmun.value=="013")//  PAMPÁN
		{
			f.cmbcodloc.options[1]= new Option('PAMPAN','001');			
			f.cmbcodloc.options[2]= new Option('FLOR DE PATRIA','002');			
			f.cmbcodloc.options[3]= new Option('LA PAZ','003');			
			f.cmbcodloc.options[4]= new Option('SANTA ANA','004');			
		}
		if(f.cmbcodmun.value=="014")//  PAMPANITO
		{
			f.cmbcodloc.options[1]= new Option('PAMPANITO','001');			
			f.cmbcodloc.options[2]= new Option('LA CONCEPCION','002');			
			f.cmbcodloc.options[3]= new Option('PAMPANITO II','003');			
		}
		if(f.cmbcodmun.value=="015")//  RAFAEL RANGEL
		{
			f.cmbcodloc.options[1]= new Option('BETIJOQUE','001');			
			f.cmbcodloc.options[2]= new Option('LA PUEBLITA','002');			
			f.cmbcodloc.options[3]= new Option('LOS CEDROS','003');			
			f.cmbcodloc.options[4]= new Option('JOSE GREGORIO HERNANDEZ','004');			
		}
		if(f.cmbcodmun.value=="016")//  SAN RAFAEL DE CARVAJAL
		{
			f.cmbcodloc.options[1]= new Option('CARVAJAL','001');			
			f.cmbcodloc.options[2]= new Option('ANTONIO NICOLAS BRICEÑO','002');			
			f.cmbcodloc.options[3]= new Option('CAMPO ALEGRE','003');			
			f.cmbcodloc.options[4]= new Option('JOSE LEONARDO SUAREZ','004');			
		}
		if(f.cmbcodmun.value=="017")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('SABANA DE MENDOZA','001');			
			f.cmbcodloc.options[2]= new Option('EL PARAISO','002');			
			f.cmbcodloc.options[3]= new Option('JUNIN','003');			
			f.cmbcodloc.options[4]= new Option('VALMORE RODRIGUEZ','004');			
		}
		if(f.cmbcodmun.value=="018")//  TRUJILLO
		{
			f.cmbcodloc.options[1]= new Option('ANDRES LINAREZ','001');			
			f.cmbcodloc.options[2]= new Option('CHIQUINQUIRA','002');			
			f.cmbcodloc.options[3]= new Option('CRISTOBAL MENDOZA','003');			
			f.cmbcodloc.options[4]= new Option('CRUZ CARRILLO','004');			
			f.cmbcodloc.options[5]= new Option('MATRIZ','005');			
			f.cmbcodloc.options[6]= new Option('MONSEÑOR CARRILLO','006');			
			f.cmbcodloc.options[7]= new Option('TRES ESQUINAS','007');			
		}
		if(f.cmbcodmun.value=="019")//  URDANETA
		{
			f.cmbcodloc.options[1]= new Option('LA QUEBRADA','001');			
			f.cmbcodloc.options[2]= new Option('CABIMBU','002');			
			f.cmbcodloc.options[3]= new Option('JAJO','003');			
			f.cmbcodloc.options[4]= new Option('LA MESA','004');			
			f.cmbcodloc.options[5]= new Option('SANTIAGO','005');			
			f.cmbcodloc.options[6]= new Option('TUÑAME','006');			
		}
		if(f.cmbcodmun.value=="020")//  VALERA
		{
			f.cmbcodloc.options[1]= new Option('JUAN IGNACIO MONTILLA','001');			
			f.cmbcodloc.options[2]= new Option('BEATRIZ','002');			
			f.cmbcodloc.options[3]= new Option('MERCEDES DIAZ','003');			
			f.cmbcodloc.options[4]= new Option('SAN LUIS 3','004');			
			f.cmbcodloc.options[5]= new Option('LA PUERTA','005');			
			f.cmbcodloc.options[6]= new Option('MENDOZA','006');			
		}
	}
	if(f.cmbcodent.value=="22")// YARACUY
	{
		if(f.cmbcodmun.value=="001")//  ARISTIDES BASTIDAS
		{
		}
		if(f.cmbcodmun.value=="002")//  BOLÍVAR
		{
		}
		if(f.cmbcodmun.value=="003")//  BRUZUAL
		{
			f.cmbcodloc.options[1]= new Option('BRUZUAL','001');			
			f.cmbcodloc.options[2]= new Option('CAMPO ELIAS','002');			
		}
		if(f.cmbcodmun.value=="004")//  COCOROTE
		{
		}
		if(f.cmbcodmun.value=="005")//  INDEPENDENCIA
		{
		}
		if(f.cmbcodmun.value=="006")//  JOSÉ ANTONIO PÁEZ
		{
		}
		if(f.cmbcodmun.value=="007")//  LA TRINIDAD
		{
		}
		if(f.cmbcodmun.value=="008")//  MANUEL MONGE
		{
		}
		if(f.cmbcodmun.value=="009")//  NIRGUA
		{
			f.cmbcodloc.options[1]= new Option('NIRGUA','001');			
			f.cmbcodloc.options[2]= new Option('SALOM','002');			
			f.cmbcodloc.options[3]= new Option('TEMERLA','003');			
		}
		if(f.cmbcodmun.value=="010")//  PEÑA
		{
			f.cmbcodloc.options[1]= new Option('PEÑA','001');			
			f.cmbcodloc.options[2]= new Option('SAN ANDRES','002');			
		}
		if(f.cmbcodmun.value=="011")//  SAN FELIPE
		{
			f.cmbcodloc.options[1]= new Option('SAN FELIPE','001');			
			f.cmbcodloc.options[2]= new Option('ALBARICO','002');			
			f.cmbcodloc.options[3]= new Option('SAN JAVIER','003');			
		}
		if(f.cmbcodmun.value=="012")//  SUCRE
		{
		}
		if(f.cmbcodmun.value=="013")//  URACHICHE
		{
		}
		if(f.cmbcodmun.value=="014")//  VEROES
		{
			f.cmbcodloc.options[1]= new Option('VEROES','001');			
			f.cmbcodloc.options[2]= new Option('EL GUAYABO','002');			
		}
	}
	if(f.cmbcodent.value=="23")// ZULIA
	{
		if(f.cmbcodmun.value=="001")//  ALMIRANTE PADILLA
		{
			f.cmbcodloc.options[1]= new Option('ISLA DE TOAS','001');			
			f.cmbcodloc.options[2]= new Option('MONAGAS','002');			
		}
		if(f.cmbcodmun.value=="002")//  BARALT
		{
			f.cmbcodloc.options[1]= new Option('SAN TIMOTEO','001');			
			f.cmbcodloc.options[2]= new Option('GENERAL URDANETA','002');			
			f.cmbcodloc.options[3]= new Option('LIBERTADOR','003');			
			f.cmbcodloc.options[4]= new Option('MANUEL GUANIPA MATOS','004');			
			f.cmbcodloc.options[5]= new Option('MARCELINO BRICEÑO','005');			
			f.cmbcodloc.options[6]= new Option('PUEBLO NUEVO','006');			
		}
		if(f.cmbcodmun.value=="003")//  CABIMAS
		{
			f.cmbcodloc.options[1]= new Option('AMBROSIO','001');			
			f.cmbcodloc.options[2]= new Option('CARMEN HERRERA','002');			
			f.cmbcodloc.options[3]= new Option('GERMAN RIOS LINARES','003');			
			f.cmbcodloc.options[4]= new Option('LA ROSA','004');			
			f.cmbcodloc.options[5]= new Option('JORGE HERNANDEZ','005');			
			f.cmbcodloc.options[6]= new Option('ROMULO BETANCOURT','006');			
			f.cmbcodloc.options[7]= new Option('SAN BENITO','007');			
			f.cmbcodloc.options[8]= new Option('ARISTIDES CALVANI','008');			
			f.cmbcodloc.options[9]= new Option('PUNTA GORDA','009');			
		}
		if(f.cmbcodmun.value=="004")//  CATATUMBO
		{
			f.cmbcodloc.options[1]= new Option('ENCONTRADOS','001');			
			f.cmbcodloc.options[2]= new Option('UDON PEREZ','002');			
		}
		if(f.cmbcodmun.value=="005")//  COLÓN
		{
			f.cmbcodloc.options[1]= new Option('SAN CARLOS DEL ZULIA','001');			
			f.cmbcodloc.options[2]= new Option('MORALITO','002');			
			f.cmbcodloc.options[3]= new Option('SANTA BARBARA','003');			
			f.cmbcodloc.options[4]= new Option('SANTA CRUZ DEL ZULIA','004');			
			f.cmbcodloc.options[5]= new Option('URRIBARRI','005');			
		}
		if(f.cmbcodmun.value=="006")//  FRANCISCO JAVIER PULGAR
		{
			f.cmbcodloc.options[1]= new Option('SIMON RODRIGUEZ','001');			
			f.cmbcodloc.options[2]= new Option('CARLOS QUEVEDO','002');			
			f.cmbcodloc.options[3]= new Option('FRANCISCO JAVIER PULGAR','003');			
		}
		if(f.cmbcodmun.value=="007")//  JESÚS ENRIQUE LOSSADA
		{
			f.cmbcodloc.options[1]= new Option('LA CONCEPCION','001');			
			f.cmbcodloc.options[2]= new Option('JOSE RAMON YEPEZ','002');			
			f.cmbcodloc.options[3]= new Option('MARIANO PARRA LEON','003');			
			f.cmbcodloc.options[4]= new Option('SAN JOSE','004');			
		}
		if(f.cmbcodmun.value=="008")//  JESÚS MARÍA SEMPRÚN
		{
			f.cmbcodloc.options[1]= new Option('JESUS MARIA SEMPRUN','001');			
			f.cmbcodloc.options[2]= new Option('BARI','002');			
		}
		if(f.cmbcodmun.value=="009")//  LA CAÑADA DE URDANETA
		{
			f.cmbcodloc.options[1]= new Option('CONCEPCION','001');			
			f.cmbcodloc.options[2]= new Option('ANDRES BELLO','002');			
			f.cmbcodloc.options[3]= new Option('CHIQUINQUIRA','003');			
			f.cmbcodloc.options[4]= new Option('EL CARMELO','004');			
			f.cmbcodloc.options[5]= new Option('POTRERITOS','005');			
		}
		if(f.cmbcodmun.value=="010")//  LAGUNILLAS
		{
			f.cmbcodloc.options[1]= new Option('ALONSO DE OJEDA','001');			
			f.cmbcodloc.options[2]= new Option('LIBERTAD','002');			
			f.cmbcodloc.options[3]= new Option('CAMPO LARA','003');			
			f.cmbcodloc.options[4]= new Option('ELEAZAR LOPEZ CONTRERAS','004');			
			f.cmbcodloc.options[5]= new Option('VENEZUELA','005');			
		}
		if(f.cmbcodmun.value=="011")//  MACHIQUES DE PERIJÁ
		{
			f.cmbcodloc.options[1]= new Option('LIBERTAD','001');			
			f.cmbcodloc.options[2]= new Option('BARTOLOME DE LAS CASAS','002');			
			f.cmbcodloc.options[3]= new Option('RIO NEGRO','003');			
			f.cmbcodloc.options[4]= new Option('SAN JOSE DE PERIJA','004');			
		}
		if(f.cmbcodmun.value=="012")//  MARA
		{
			f.cmbcodloc.options[1]= new Option('SAN RAFAEL','001');			
			f.cmbcodloc.options[2]= new Option('LA SIERRITA','002');			
			f.cmbcodloc.options[3]= new Option('LAS PARCELAS','003');			
			f.cmbcodloc.options[4]= new Option('LUIS DE VICENTE','004');			
			f.cmbcodloc.options[5]= new Option('MONSEÑOR MARCOS SERGIO GODOY','005');			
			f.cmbcodloc.options[6]= new Option('RICAURTE','006');			
			f.cmbcodloc.options[7]= new Option('TAMARE','007');			
		}
		if(f.cmbcodmun.value=="013")//  MARACAIBO
		{
			f.cmbcodloc.options[1]= new Option('ANTONIO BORJAS ROMERO','001');			
			f.cmbcodloc.options[2]= new Option('BOLIVAR','002');			
			f.cmbcodloc.options[3]= new Option('CACIQUE MARA','003');			
			f.cmbcodloc.options[4]= new Option('CARACCIOLO PARRA PEREZ','004');			
			f.cmbcodloc.options[5]= new Option('CECILIO ACOSTA','005');			
			f.cmbcodloc.options[6]= new Option('CRISTO DE ARANZA','006');			
			f.cmbcodloc.options[7]= new Option('COQUIVACOA','007');			
			f.cmbcodloc.options[8]= new Option('CHIQUINQUIRA','008');			
			f.cmbcodloc.options[9]= new Option('FRANCISCO EUGENIO BUSTAMANTE','009');			
			f.cmbcodloc.options[10]= new Option('IDELFONSO VASQUEZ','010');			
			f.cmbcodloc.options[11]= new Option('JUANA DE AVILA','011');			
			f.cmbcodloc.options[12]= new Option('LUIS HURTADO HIGUERA','012');			
			f.cmbcodloc.options[13]= new Option('MANUEL DAGNINO','013');			
			f.cmbcodloc.options[14]= new Option('OLEGARIO VILLALOBOS','014');			
			f.cmbcodloc.options[15]= new Option('RAUL LEONI','015');			
			f.cmbcodloc.options[16]= new Option('SANTA LUCIA','016');			
			f.cmbcodloc.options[17]= new Option('VENANCIO PULGAR','017');			
			f.cmbcodloc.options[18]= new Option('SAN ISIDRO','018');			
		}
		if(f.cmbcodmun.value=="014")//  MIRANDA
		{
			f.cmbcodloc.options[1]= new Option('ALTAGRACIA','001');			
			f.cmbcodloc.options[2]= new Option('ANA MARIA CAMPOS','002');			
			f.cmbcodloc.options[3]= new Option('FARIA','003');			
			f.cmbcodloc.options[4]= new Option('SAN ANTONIO','004');			
			f.cmbcodloc.options[5]= new Option('SAN JOSE','005');			
		}
		if(f.cmbcodmun.value=="015")//  PÁEZ
		{
			f.cmbcodloc.options[1]= new Option('SINAMAICA','001');			
			f.cmbcodloc.options[2]= new Option('ALTA GUAJIRA','002');			
			f.cmbcodloc.options[3]= new Option('ELIAS SANCHEZ RUBIO','003');			
			f.cmbcodloc.options[4]= new Option('GUAJIRA','004');			
		}
		if(f.cmbcodmun.value=="016")//  ROSARIO DE PERIJÁ
		{
			f.cmbcodloc.options[1]= new Option('EL ROSARIO','001');			
			f.cmbcodloc.options[2]= new Option('DONALDO GARCIA','002');			
			f.cmbcodloc.options[3]= new Option('SIXTO ZAMBRANO','003');			
		}
		if(f.cmbcodmun.value=="017")//  SAN FRANCISCO
		{
			f.cmbcodloc.options[1]= new Option('SAN FRANCISCO','001');			
			f.cmbcodloc.options[2]= new Option('EL BAJO','002');			
			f.cmbcodloc.options[3]= new Option('DOMITILA FLORES','003');			
			f.cmbcodloc.options[4]= new Option('FRANCISCO OCHOA','004');			
			f.cmbcodloc.options[5]= new Option('LOS CORTIJOS','005');			
			f.cmbcodloc.options[6]= new Option('MARCIAL HERNANDEZ','006');			
		}
		if(f.cmbcodmun.value=="018")//  SANTA RITA
		{
			f.cmbcodloc.options[1]= new Option('SANTA RITA','001');			
			f.cmbcodloc.options[2]= new Option('EL MENE','002');			
			f.cmbcodloc.options[3]= new Option('JOSE CENOVIO URRIBARRI','003');			
			f.cmbcodloc.options[4]= new Option('PEDRO LUCAS URRIBARRI','004');			
		}
		if(f.cmbcodmun.value=="019")//  SIMÓN BOLÍVAR
		{
			f.cmbcodloc.options[1]= new Option('MANUEL MANRIQUE','001');			
			f.cmbcodloc.options[2]= new Option('RAFAEL MARIA BARALT','002');			
			f.cmbcodloc.options[3]= new Option('RAFAEL URDANETA','003');			
		}
		if(f.cmbcodmun.value=="020")//  SUCRE
		{
			f.cmbcodloc.options[1]= new Option('BOBURES','001');			
			f.cmbcodloc.options[2]= new Option('EL BATEY','002');			
			f.cmbcodloc.options[3]= new Option('GIBRALTAR','003');			
			f.cmbcodloc.options[4]= new Option('HERAS','004');			
			f.cmbcodloc.options[5]= new Option('MONSEÑOR ARTURO CELESTINO ALVAREZ','005');			
			f.cmbcodloc.options[6]= new Option('ROMULO GALLEGOS','006');			
		}
		if(f.cmbcodmun.value=="021")//  VALMORE RODRÍGUEZ
		{
			f.cmbcodloc.options[1]= new Option('LA VICTORIA','001');			
			f.cmbcodloc.options[2]= new Option('RAFAEL URDANETA','002');			
			f.cmbcodloc.options[3]= new Option('RAUL CUENCA','003');			
		}
	}
	if(f.cmbcodent.value=="24")// VARGAS
	{
		if(f.cmbcodmun.value=="001")//  VARGAS
		{
			f.cmbcodloc.options[1]= new Option('CARABALLERA','001');			
			f.cmbcodloc.options[2]= new Option('CARAYACA','002');			
			f.cmbcodloc.options[3]= new Option('CARUAO','003');			
			f.cmbcodloc.options[4]= new Option('CATIA LA MAR','004');			
			f.cmbcodloc.options[5]= new Option('EL JUNKO','005');			
			f.cmbcodloc.options[6]= new Option('LA GUAIRA','006');			
			f.cmbcodloc.options[7]= new Option('MACUTO','007');			
			f.cmbcodloc.options[8]= new Option('MAIQUETIA','008');			
			f.cmbcodloc.options[9]= new Option('NAIGUATA','009');			
			f.cmbcodloc.options[10]= new Option('RAUL LEONI','010');			
			f.cmbcodloc.options[11]= new Option('CARLOS SOUBLETTE','011');			
		}
	}
}
