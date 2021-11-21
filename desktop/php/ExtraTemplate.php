<?php
/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
    throw new \Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('ExtraTemplate');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

    <div class="row row-overflow">
        <div class="col-lg-2 col-md-3 col-sm-4">
            <div class="bs-sidebar">
                <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                    <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"
                       data-action="add">
                        <i class="fa fa-plus-circle"></i> {{Ajouter un ExtraTemplate}}
                    </a>
                    <li class="filter" style="margin-bottom: 5px;">
                        <input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/>
                    </li>
                    <?php
                    foreach ($eqLogics as $eqLogic) {
                        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                        echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay"
             style="border-left: solid 1px #EEE; padding-left: 25px;">
            <legend><i class="fa fa-cog"></i> {{Gestion}}</legend>
            <div class="eqLogicThumbnailContainer">
                <div class="cursor eqLogicAction" data-action="add"
                     style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                    <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
                    <br>
                    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">{{Ajouter}}</span>
                </div>
                <div class="cursor eqLogicAction" data-action="gotoPluginConf"
                     style="text-align: center; background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
                    <i class="fa fa-wrench" style="font-size : 6em;color:#767676;"></i>
                    <br>
                    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676">{{Configuration}}</span>
                </div>
            </div>
            <legend><i class="fa fa-table"></i> {{Mes ExtraTemplates}}</legend>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="text-align: center; background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
                    echo '<img src="' . $plugin->getPathImgIcon() . '" height="105" width="95" />';
                    echo "<br>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogic"
             style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
            <a class="btn btn-success eqLogicAction pull-right" data-action="save">
                <i class="fa fa-check-circle"></i> {{Sauvegarder}}
            </a>
            <a class="btn btn-danger eqLogicAction pull-right" data-action="remove">
                <i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-default eqLogicAction pull-right" data-action="configure">
                <i class="fa fa-cogs"></i> {{Configuration avancée}}
            </a>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation">
                    <a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab"
                       data-action="returnToThumbnailDisplay">
                        <i class="fa fa-arrow-circle-left"></i>
                    </a>
                </li>
                <li role="presentation" class="active">
                    <a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
                        <i class="fa fa-tachometer"></i> {{Equipement}}
                    </a>
                </li>
                <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
                        <i class="fa fa-list-alt"></i> {{Commandes}}</a>
                </li>
            </ul>
            <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                    <br/>
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="name">{{Nom de l'équipement
                                    ExtraTemplate}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id"
                                           style="display : none;"/>
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" id="name"
                                           placeholder="{{Nom de l'équipement ExtraTemplate}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="sel_object">{{Objet parent}}</label>
                                <div class="col-sm-3">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
                                        <?php
                                        foreach (jeeObject::all() as $object) {
                                            echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                <div class="col-sm-9">
                                    <?php
                                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                        echo '<label class="checkbox-inline" for="category-' . $key . '">';
                                        echo '<input type="checkbox" id="category-' . $key . '" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                        echo '</label>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <label class="checkbox-inline" for="is-enable">
                                        <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable"
                                               checked="checked" id="is-enable"/>
                                        {{Activer}}
                                    </label>
                                    <label class="checkbox-inline" for="is-visible">
                                        <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible"
                                               checked="checked" id="is-visible"/>
                                        {{Visible}}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="ExtraTemplate-param">{{ExtraTemplate param
                                    1}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" id="ExtraTemplate-param"
                                           data-l1key="configuration" data-l2key="city" placeholder="param1"/>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="commandtab">
                    <a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;">
                        <i class="fa fa-plus-circle"></i> {{Commandes}}
                    </a>
                    <br/>
                    <br/>
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th>{{Nom}}</th>
                            <th>{{Type}}</th>
                            <th>{{Action}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

<?php
include_file('desktop', 'ExtraTemplate', 'js', 'ExtraTemplate');
include_file('core', 'plugin.template', 'js');
