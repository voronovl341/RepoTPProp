protected function providers()
	{

		if(empty(self::providersHL)) return false;
        if(empty($this->siteId)) return false;

		$this->HLload(self::providersHL);
		$rsData = $this->strEntityDataClass::getList(array(
			'filter' => array('*'),
			'select' => array('UF_NAME', 'ID'),
			'order' => array('ID' => 'DESC')
		));

		while($arItem = $rsData->Fetch()) {
			$arIdProv[$arItem['UF_NAME']] = $arItem['ID'];
		}

		// if(empty($_SESSION['lastid']['providers'])){
		// 	$lastId = 0;
		// } else {
		// 	$lastId = $_SESSION['lastid']['providers'];
		// }
		$lastId = 0;
		//Тянем недостающие элементы
		$Providers = $this->send("get", "providers", $lastId);
		// pp($arIdProv);
		// pp($Providers);
		// if(!empty($Providers)) {
		// 	$_SESSION['lastid']['providers'] = max(array_keys($Providers));
		// 	echo '<script>
		// 	startCountdown();
		// 	function reload (){document.location.href = location.href};setTimeout("reload()", 2000);
		// 	</script>';

		// } else {
		// 	$_SESSION['lastid']['providers'] = 0;
		// 	echo 'Все необходимые элементы добавлены и обновлены<br>';
		// }
		// foreach ($Providers as $kpr => $vpr) {
		// 	$kpr = trim($kpr);
		// 	$Providers[$kpr] = trim($vpr);
		// }

		$countAdd = 0;
		$countUpdate = 0;
		if(!empty($Providers)){
			//Заносим 
			foreach($Providers as $kData => $value){
				// if(!empty($arIdProv[$value['VALUE']])){
					$strval = trim($value['VALUE']);
					$strxml = trim($value['XML_ID']);

					if(array_key_exists($strval, $arIdProv)){
						$update = $this->strEntityDataClass::update($arIdProv[$strval], array(
					      	'UF_SORT_ID'			=> $value['SORT'],
					      	'UF_SORT'				=> $value['SORT'],
					      	'UF_XML_ID'        	 	=> $strxml,
					      	'UF_NAME'				=> $strval
					   		));
						$countUpdate++;

					} else {
						$this->strEntityDataClass::add(array(
					      	'UF_SORT_ID'			=> $value['SORT'],
					      	'UF_SORT'				=> $value['SORT'],
					      	'UF_XML_ID'        	 	=> $strxml,
					      	'UF_NAME'				=> $strval
					   		));
						$countAdd++;

					}
				// }
			}
		if(count($arIdProv)>0) echo count($arIdProv).' элементов в базе<br>';
		if($countAdd) echo 'Добавлено '.$countAdd.' элементов<br>';
		if($countUpdate) echo 'Обновлено '.$countUpdate.' элементов<br>';
		}
	}
