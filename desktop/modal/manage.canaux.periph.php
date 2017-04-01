<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>
<div id='div_domonialCanauxPAlert' style="display: none;"></div>
<a class="btn btn-success pull-right" id="bt_saveDomonialCanauxP"><i class="fa fa-check-circle"></i> Enregistrer</a><br/><br/><br/>

<table id="table_domonialCanauxP" class="table table-bordered table-condensed tablesorter">
    <thead>
        <tr>
            <th>{{Nom}}</th>
            <th>{{Apparition Défaut}}</th>
			<th>{{Disparition Défaut}}</th>
			<th>{{Actif}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph1N" placeholder="Peripherique 1" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph1A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph1D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph1U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph2N" placeholder="Peripherique 2" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph2A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph2D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph2U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph3N" placeholder="Peripherique 3" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph3A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph3D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph3U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph4N" placeholder="Peripherique 4" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph4A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph4D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph4U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph5N" placeholder="Peripherique 5" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph5A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph5D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph5U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph6N" placeholder="Peripherique 6" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph6A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph6D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph6U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph7N" placeholder="Peripherique 7" /></td>
            <td><input type="text" maxlength="2"  class="configKey" data-l1key="canaux::Periph7A" /></td>
			<td><input type="text" maxlength="2"  class="configKey" data-l1key="canaux::Periph7D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph7U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph8N" placeholder="Peripherique 8" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph8A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph8D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph8U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph9N" placeholder="Peripherique 9" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph9A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph9D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph9U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph10N" placeholder="Peripherique 10" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph10A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph10D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph10U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph11N" placeholder="Peripherique 11" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph11A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph11D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph11U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph12N" placeholder="Peripherique 12" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph12A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph12D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph12U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph13N" placeholder="Peripherique 13" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph13A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph13D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph13U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph14N" placeholder="Peripherique 14" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph14A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph14D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph14U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph15N" placeholder="Peripherique 15" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph15A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph15D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph15U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph16N" placeholder="Peripherique 16" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph16A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph16D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph16U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph17N" placeholder="Peripherique 17" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph17A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph17D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph17U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph18N" placeholder="Peripherique 18" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph18A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph18D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph18U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph19N" placeholder="Peripherique 19" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph19A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph19D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph19U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph20N" placeholder="Peripherique 20" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph20A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph20D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph20U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph21N" placeholder="Peripherique 21" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph21A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph21D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph21U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph22N" placeholder="Peripherique 22" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph22A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph22D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph22U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph23N" placeholder="Peripherique 23" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph23A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph23D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph23U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph24N" placeholder="Peripherique 24" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph24A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph24D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph24U" /></td>
        </tr>
        <tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph25N" placeholder="Peripherique 25" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph25A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph25D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph25U" /></td>
        </tr>		
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph26N" placeholder="Peripherique 26" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph26A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph26D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph26U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph27N" placeholder="Peripherique 27" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph27A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph27D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph27U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph28N" placeholder="Peripherique 28" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph28A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph28D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph28U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph29N" placeholder="Peripherique 29" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph29A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph29D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph29U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph30N" placeholder="Peripherique 30" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph30A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph30D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph30U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph31N" placeholder="Peripherique 31" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph31A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph31D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph31U" /></td>
        </tr>
		<tr>
            <td><input type="text" class="configKey" data-l1key="canaux::Periph32N" placeholder="Peripherique 32" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph32A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Periph32D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Periph32U" /></td>
        </tr>
    </tbody>
</table>

<script>
    initTableSorter();

 jeedom.config.load({
    configuration: $('#table_domonialCanauxP').getValues('.configKey')[0],
    plugin: 'domonial',
    error: function (error) {
        $('#div_domonialCanauxPAlert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#table_domonialCanauxP').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});
 $("#bt_saveDomonialCanauxP").on('click', function (event) {
    $.hideAlert();
    jeedom.config.save({
        configuration: $('#table_domonialCanauxP').getValues('.configKey')[0],
        plugin: 'domonial',
        error: function (error) {
            $('#div_domonialCanauxPAlert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_domonialCanauxPAlert').showAlert({message: '{{Sauvegarde réussie.}}', level: 'success'});
        }
    });
});

</script>

