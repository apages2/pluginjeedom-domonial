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
?>
<div id='div_domonialCanauxSAlert' style="display: none;"></div>
<a class="btn btn-success pull-right" id="bt_saveDomonialCanauxS"><i class="fa fa-check-circle"></i> Enregistrer</a><br/><br/><br/>

<table id="table_domonialCanauxS" class="table table-bordered table-condensed tablesorter">
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
            <td><input type="text" readonly value="Reset" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ResetA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ResetD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ResetU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Batterie" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::BatterieA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::BatterieD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::BatterieU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Secteur" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::SecteurA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::SecteurD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::SecteurU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Brouillage" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::BrouillageA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::BrouillageD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::BrouillageU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Ligne Telephonique" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::LigneTelA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::LigneTelD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::LigneTelU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="AutoProtection Periph" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APPeriphA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APPeriphD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::APPeriphU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Pile" /></td>
            <td><input type="text" maxlength="2"  class="configKey" data-l1key="canaux::PileA" /></td>
			<td><input type="text" maxlength="2"  class="configKey" data-l1key="canaux::PileD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::PileU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Supervision" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::SupervisionA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::SupervisionD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::SupervisionU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Test Cyclique 1" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TestCyclique1A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TestCyclique1D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::TestCyclique1U" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Test Cyclique 2" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TestCyclique2A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TestCyclique2D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::TestCyclique2U" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Teletest" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TeleTestA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TeleTestD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::TeleTestU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Codes Erronés" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::CodeErronesA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::CodeErronesD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::CodeErronesU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Code sous Contrainte" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::CodeSousContrainteA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::CodeSousContrainteD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::CodeSousContrainteU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Intervenant" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::IntervenantA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::IntervenantD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::IntervenantU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Reseau GSM" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ReseauGSMA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ReseauGSMD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ReseauGSMU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Alarme de Synthese" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AlarmeSyntheseA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AlarmeSyntheseD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::AlarmeSyntheseU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Memo Alarme" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::MemoAlarmeA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::MemoAlarmeD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::MemoAlarmeU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Arret Confirmé" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ArretConfirmeA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ArretConfirmeD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ArretConfirmeU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Ejection" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::EjectionA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::EjectionD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::EjectionU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="AutoProtection Centrale" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APCentraleA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APCentraleD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::APCentraleU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Mode Transparent" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ModeTransparentA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ModeTransparentD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ModeTransparentU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Totale" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TotaleA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TotaleD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::TotaleU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Partielle" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::PartielleA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::PartielleD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::PartielleU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Annexe" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AnnexeA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AnnexeD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::AnnexeU" /></td>
        </tr>
        <tr>
            <td><input type="text" readonly value="Pointage" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::PointageA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::PointageD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::PointageU" /></td>
        </tr>		
		<tr>
            <td><input type="text" readonly value="Image Prete" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImagePreteA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImagePreteD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ImagePreteU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Image KO" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImageKOA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImageKOD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ImageKOU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Image Confort" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImageConfortA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ImageConfortD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ImageConfortU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Derangement Incendie" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DerangementIncendieA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DerangementIncendieD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::DerangementIncendieU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Defaut Pile Utilisateur" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefPileUserA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefPileUserD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::DefPileUserU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Autoprotection Utilisateur" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APUserA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::APUserD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::APUserU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="TCU Hors Support" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TCUHorsSupportA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::TCUHorsSupportD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::TCUHorsSupportU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Confirmation d'Alarme" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ConformationAlarmeA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::ConformationAlarmeD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::ConformationAlarmeU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Defaut GPRS" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefGPRSA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefGPRSD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::DefGPRSU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Defaut Ethernet" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefEthernetA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::DefEthernetD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::DefEthernetU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Alarme Chaleur" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AlarmeChaleurA" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::AlarmeChaleurD" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::AlarmeChaleurU" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Alerte 1" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte1A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte1D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Alerte1U" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Alerte 2" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte2A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte2D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Alerte2U" /></td>
        </tr>
		<tr>
            <td><input type="text" readonly value="Alerte 3" /></td>
            <td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte3A" /></td>
			<td><input type="text" maxlength="2" class="configKey" data-l1key="canaux::Alerte3D" /></td>
			<td><input type="checkbox" class="configKey" data-l1key="canaux::Alerte3U" /></td>
        </tr>
    </tbody>
</table>

<script>
    initTableSorter();

 jeedom.config.load({
    configuration: $('#table_domonialCanauxS').getValues('.configKey')[0],
    plugin: 'domonial',
    error: function (error) {
        $('#div_domonialCanauxSAlert').showAlert({message: error.message, level: 'danger'});
    },
    success: function (data) {
        $('#table_domonialCanauxS').setValues(data, '.configKey');
        modifyWithoutSave = false;
    }
});
 $("#bt_saveDomonialCanauxS").on('click', function (event) {
    $.hideAlert();
    jeedom.config.save({
        configuration: $('#table_domonialCanauxS').getValues('.configKey')[0],
        plugin: 'domonial',
        error: function (error) {
            $('#div_domonialCanauxSAlert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_domonialCanauxSAlert').showAlert({message: '{{Sauvegarde réussie.}}', level: 'success'});
        }
    });
});

</script>

