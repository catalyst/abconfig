<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing experiments
 *
 * @package   tool_abconfig
 * @author    Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_abconfig\form;

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

/**
 * Form class for editing experiments
 *
 * @package   tool_abconfig
 * @author    Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class edit_experiment extends \moodleform {

    /**
     * Form definition
     * @return void
     */
    public function definition() {
        $mform = $this->_form;

        // Hidden form element for experiment id.
        $mform->addElement('hidden', 'id', '');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'prevshortname', '');
        $mform->setType('prevshortname', PARAM_ALPHANUM);

        // EID to pass to table generation.
        $eid = $this->_customdata['eid'];

        // Display the basic experiment information.
        $mform->addElement('header', 'experimentinfo', get_string('formexperimentinfo', 'tool_abconfig'));

        $mform->addElement('text', 'experimentname', get_string('name', 'tool_abconfig'), '');
        $mform->setType('experimentname', PARAM_TEXT);
        $mform->addRule('experimentname', get_string('formexperimentnamereq', 'tool_abconfig'), 'required');

        $mform->addElement('text', 'experimentshortname', get_string('shortname', 'tool_abconfig'), '');
        $mform->setType('experimentshortname', PARAM_ALPHANUM);
        $mform->addRule('experimentshortname', get_string('formexperimentshortnamereq', 'tool_abconfig'), 'required');

        // Setup Data array for scopes.
        $scopes = [
            'request' => get_string('request', 'tool_abconfig'),
            'session' => get_string('session', 'tool_abconfig'),
            'device' => get_string('device', 'tool_abconfig'),
        ];
        $mform->addElement('select', 'scope', get_string('formexperimentscopeselect', 'tool_abconfig'), $scopes);

        $mform->addElement('text', 'numoffset', get_string('offset', 'tool_abconfig'));
        $mform->setType('numoffset', PARAM_INT);
        $mform->hideIf('numoffset', 'scope', 'neq', 'device');
        $mform->addRule('numoffset', get_string('err_numeric', 'form'), 'numeric', null, 'client');
        $mform->addRule('numoffset', get_string('maximumchars', '', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('numoffset', 'offset', 'tool_abconfig');

        // Enabled checkbox.
        $mform->addElement('advcheckbox', 'enabled', get_string('formexperimentenabled', 'tool_abconfig'));

        // Admin Enabled Checkbox.
        $mform->addElement('advcheckbox', 'adminenabled', '', get_string('formexperimentadminenable', 'tool_abconfig'));
        $mform->hideIf('adminenabled', 'scope', 'eq', 'device');

        // Delete experiment checkbox.
        $mform->addElement('advcheckbox', 'delete', get_string('formdeleteexperiment', 'tool_abconfig'));

        // Experiment conditions.
        $mform->addElement('header', 'experimentconds', get_string('formexperimentconds', 'tool_abconfig'));

        $mform->addElement('html', \tool_abconfig\local\table_manager::conditions_table($eid));

        // Setup button group.
        $buttonarray = array();
        $buttonarray[] =& $mform->createElement('submit', 'savechanges', get_string('save'));
        $buttonarray[] =& $mform->createElement('submit', 'conditions', get_string('formeditconditions', 'tool_abconfig'));
        $mform->registerNoSubmitButton('conditions');
        $mform->closeHeaderBefore('conditions');
        $buttonarray[] =& $mform->createElement('cancel', 'cancel', get_string('cancel'));

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
    }

    /**
     * Form validation
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        return $errors;
    }
}
