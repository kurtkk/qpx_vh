<?php
/*                                                                        *
 * This script belongs to the TYPO3 package "my_extension".               *

 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *

 *                                                                        */

namespace kupix\qpxviewhelper\ViewHelpers;

use FluidTYPO3\Vhs\ViewHelpers\Asset\AbstractAssetViewHelper;

/**
 * @package my_extension
 * @subpackage ViewHelpers
 * @author Name
 */
class ModifyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	public function render( $myText = null, $header = NULL, $crop = NULL ) {
		if( $myText === null )
			$myText = $this->renderChildren();
		$retval = '';
		if ($header)
			$retval .= '<h2>'.$header.'</h2>';
		$retval .= '<p>';
		if ($crop)
			$retval .= substr($myText,0,$crop);
		else {
			$retval .= $myText;
		}
		$retval .= '</p>';
		return $retval;
	}
}
?>
