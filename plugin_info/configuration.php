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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
    <fieldset>
        <legend>{{Général}}</legend>
		<div class="form-group">
			<label class="col-sm-4 control-label">{{Enregistrer tous les messages (cela peut ralentir le système)}}</label>
			<div class="col-sm-1">
				<input type="checkbox" class="configKey" data-l1key="enableLogging" />
			</div>
			<div class="col-sm-7">
				<a class="btn btn-default" id="bt_logDomonialMessage"><i class="fa fa-file-o"></i> {{Voir les messages}}</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Pas d'acquittement de trame pour les canaux}}</label>
			<div class="col-lg-8">
				<textarea class="configKey form-control" data-l1key="bandomonialcanaux" rows="3"></textarea>
			</div>
		</div>
		<div class="form-group expertModeVisible">
            <label class="col-sm-4 control-label">{{Host socket externe (ne doit pas etre localhost)}}</label>
            <div class="col-sm-2">
                <input class="configKey form-control" data-l1key="sockethost"/>
            </div>
        </div>
        <div class="form-group expertModeVisible">
            <label class="col-sm-4 control-label">{{Port socket externe}}</label>
            <div class="col-sm-2">
                <input class="configKey form-control" data-l1key="socketport" value="55003"/>
            </div>
        </div>
		<div class="form-group">
            <label class="col-sm-4 control-label">{{Canaux Systemes}}</label>
            <div class="col-sm-2">
                <a class="btn btn-default" id="bt_manageDomonialCanauxS" ><i class="fa fa-cogs"></i> {{Gestion des canaux Systemes Domonial}}</a>
            </div>
        </div>
		<div class="form-group">
            <label class="col-sm-4 control-label">{{Canaux Peripheriques}}</label>
            <div class="col-sm-2">
                <a class="btn btn-default" id="bt_manageDomonialCanauxP" ><i class="fa fa-cogs"></i> {{Gestion des canaux Peripheriques Domonial}}
        </div>
		
    </fieldset>
</form>


<script>
    $('.bt_logDomonialMessage').on('click', function () {
     var slave_id = $(this).closest('.slaveConfig').attr('data-slave_id');
     $('#md_modal').dialog({title: "{{Log des messages domonial}}"});
     $('#md_modal').load('index.php?v=d&plugin=domonial&modal=show.log&slave_id='+slave_id).dialog('open');
 });
 
    $('#bt_logDomonialMessage').on('click', function () {
        $('#md_modal').dialog({title: "{{Log des messages domonial}}"});
        $('#md_modal').load('index.php?v=d&plugin=domonial&modal=show.log').dialog('open');
    });
	
	$('.bt_manageDomonialCanauxS').on('click', function () {
       var slave_id = $(this).closest('.slaveConfig').attr('data-slave_id');
       $('#md_modal2').dialog({title: "{{Gestion des canaux Systemes DOMONIAL}}"});
       $('#md_modal2').load('index.php?v=d&plugin=domonial&modal=manage.canaux.systeme&slave_id='+slave_id).dialog('open');
   });

    $('#bt_manageDomonialCanauxS').on('click', function () {
        $('#md_modal2').dialog({title: "{{Gestion des canaux Systemes DOMONIAL}}"});
        $('#md_modal2').load('index.php?v=d&plugin=domonial&modal=manage.canaux.systeme').dialog('open');
    });
	
	$('.bt_manageDomonialCanauxP').on('click', function () {
       var slave_id = $(this).closest('.slaveConfig').attr('data-slave_id');
       $('#md_modal2').dialog({title: "{{Gestion des canaux Peripheriques DOMONIAL}}"});
       $('#md_modal2').load('index.php?v=d&plugin=domonial&modal=manage.canaux.periph&slave_id='+slave_id).dialog('open');
   });

    $('#bt_manageDomonialCanauxP').on('click', function () {
        $('#md_modal2').dialog({title: "{{Gestion des canaux Peripheriques DOMONIAL}}"});
        $('#md_modal2').load('index.php?v=d&plugin=domonial&modal=manage.canaux.periph').dialog('open');
    });
</script>