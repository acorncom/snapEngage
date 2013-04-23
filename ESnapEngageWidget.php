<?php
/**
 * ESnapEngageWidget class file
 *
 * @author David Baker <yii@acorncomputersolutions.com>
 * @author David Baker
 * @version 1.0.0
 * @license BSD
 * @created 04.23.2013
*/

/**
 * =====Yii Snap Engage widget=====
 *
 * ===Usage:===
 * Just place:
 * <?$this->widget('ext.snapEngage.ESnapEngageWidget',
 * 		array(
 *          'account'=>'XXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
 *          'locale'=>'es',
 *          'userEmail'=>'john@smith.com',
 *      )
 * );
 * ?>
 *
 * @TODO
 * Add the ability to remove chat from mobile pages (http://help.snapengage.com/how-do-i-remove-the-snapengage-chat-from-mobile-pages/)
 * Add ability to disable editing user email address (http://help.snapengage.com/how-can-i-pre-set-the-email-address-of-my-users-when-they-are-already-logged-in-my-site/)
 * Add ability to collect additional information about the person we're chatting with (http://help.snapengage.com/collecting-additional-information-for-the-agent-at-the-beginning-of-the-chat/)
 */
class ESnapEngageWidget extends CWidget {

    /**
     * Snap Engage account ID (example: 'XXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX')
     * @var string
     */
    public $account;

    /**
     * Additional configuration information to pass to SnapEngage
     * @var string
     */
    public $additionalConfig;

    /**
     * Current locale to display information in.  Defaults to the Yii app locale if nothing is specified
     * @var string
     */
    public $locale;

    /**
     * Current user's email address (if auto-populate of the information is desired)
     * @var string
     */
    public $userEmail = "";

    public function run() {

        if($this->locale===null) {
            $this->locale = Yii::app()->language;
        }

        $this->additionalConfig .= "SnapABug.setUserEmail('{$this->userEmail}');\n";

        Yii::app()->clientScript->registerScript('SnapEngageWidget',
            "
            (function() {
                var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
                se.src = '//commondatastorage.googleapis.com/code.snapengage.com/js/{$this->account}.js';
                var done = false;
                se.onload = se.onreadystatechange = function() {
                    if (!done&&(!this.readyState||this.readyState==='loaded'||this.readyState==='complete')) {
                        done = true;

                        // Place your SnapEngage JS API code below
                        SnapABug.setLocale('{$this->locale}');
                        {$this->additionalConfig}
                    }
                };
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(se, s);
            })();
            "
            ,CClientScript::POS_END
        );
    }
}