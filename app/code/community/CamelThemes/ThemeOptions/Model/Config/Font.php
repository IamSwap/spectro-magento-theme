<?php

/**
 * CamelThemes ThemeOptions 
 * 
 * @category     CamelThemes
 * @package      CamelThemes_ThemeOptions 
 * @Class        CamelThemes_ThemeOptions_Model_Config_Dropdown
 */ 

class CamelThemes_ThemeOptions_Model_Config_Font
{
	/**
	 * google fonts list
	 *
	 * @var string
	 */
	private $gfonts = "Exo,Alegreya Sans,Alegreya Sans SC,Roboto,Source Sans Pro,Titillium Web,Josefin Sans,Josefin Slab,Lato,Open Sans,Raleway,Cabin,Expletus Sans,Fira Sans,Merriweather,Merriweather Sans,Ubuntu,Advent Pro,Dosis,Ek Mukta,Source Code Pro,Alegreya,Alegreya SC,Crimson Text,Neuton,Overlock,Playfair Display,Playfair Display SC,Roboto Condensed,Sarpanch,Halant,Hind,Karma,Khand,Laila,Rajdhani,Teko,Almendra,Amaranth,Anonymous Pro,Archivo Narrow,Arimo,Arvo,Asap,Cabin Condensed,Cantarell,Caudex,Chivo,Cousine,Cuprum,Droid Serif,Economica,GFS Neohellenic,Gentium Basic,Gentium Book Basic,Istok Web,Jura,Karla,Lobster Two,Lora,Marvel,Maven Pro,Muli,Nobile,Noticia Text,Noto Sans,Noto Serif,Orbitron,PT Sans,PT Serif,Philosopher,Puritan,Quantico,Quattrocento Sans,Rambla,Roboto Slab,Rosario,Scada,Share,Signika,Signika Negative,Simonetta,Tinos,Ubuntu Mono,Vesper Libre,Volkhov,Vollkorn,Yanone Kaffeesatz";

    public function toOptionArray()
    {
	    $fonts = explode(',', $this->gfonts);
	    $options = array();
	    foreach ($fonts as $f ){
		    $options[] = array(
			    'value' => $f,
			    'label' => $f,
		    );
	    }

        return $options;
    }

}
