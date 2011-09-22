<?php
namespace Core\Util;

class String
{
    /**
     * Aplica uma máscara à uma string
     * 
     * @param string $val valor a ser 'mascardo'
     * @param string $mask máscara a ser aplicada
     * @return string 
     * 
     * <code>
     * <?php
     * \Core\String::mask(1234567, '###.###-#') // 123.456-7
     * ?>
     * </code>
     */
    public static function mask($val, $mask)
    {
	$maskared = '';
	$k = 0;
	for ($i = 0; $i <= strlen($mask) - 1; $i++) {
	    if ($mask[$i] == '#') {
		if (isset($val[$k]))
		    $maskared .= $val[$k++];
	    } else {
		if (isset($mask[$i]))
		    $maskared .= $mask[$i];
	    }
	}
	return $maskared;
    }

}

?>
