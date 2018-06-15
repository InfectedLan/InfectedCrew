<?php
/**
 * This file is part of InfectedCrew.
 *
 * Copyright (C) 2017 Infected <http://infected.no/>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3.0 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'session.php';
require_once 'handlers/restrictedpagehandler.php';
require_once 'handlers/grouphandler.php';
require_once 'utils/crewutils.php';
require_once 'page.php';

class ApplyPage extends Page {
    public function canAccess(User $user): bool {
        return !$user->isGroupMember();
    }
    public function isPublic(): bool {
        return false;
    }
	public function getTitle(): ?string {
		return 'Ny søknad';
	}

    public function getContent(User $user = null): string {
        $content = null;

        if($user->hasAvatar()) {
            $lanName = Settings::name;
            $groupList = GroupHandler::getGroups();
            $crewCount = count($groupList);
            $applications = ApplicationHandler::getUserApplications($user);

            if(!empty($applications)) {
                $content = <<<EOD
<div class="box box-warning">
<div class="box-header with-border">
  <h3 class="box-title">Du har allerede søkt</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
    <div class="box-body">
        <p>Du har allerede søkt crew for dette året. Om du vil sjekke ut søknadene du allerede har, kan du gå <a href="?page=my-applications">hit</a>. Du kan gjerne søke en gang til for et annet crew, men vennligst ikke søk fler ganger på et crew - det vil bare gjøre opptaksprosessen tregere.</p>
    </div>
</div>
EOD;
            }

            $content .= <<<EOD
<script>
$(document).ready(function() {
    $('.application').submit(function(e) {
        e.preventDefault();

        $.getJSON('../api/json/application/addApplication.php' + '?' + $(this).serialize(), function(data) {
            if (data.result) {
                info(data.message);
            } else {
                error(data.message);
            }
        });
    });
});
</script>
<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Søknad til crew</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form class="application" method="post" role="form">
    <div class="box-body">
        <p>Velkommen! Som crew vil du oppleve ting du aldri ville som deltaker, få erfaringer du kan bruke sette på din CV-en, <br>
og møte mange nye og spennende mennesker. Dersom det er første gang du skal søke til crew på $lanName, <br>
anbefaler vi at du leser igjennom beskrivelsene av våre $crewCount forksjellige crew. Disse finner du <a href="index.php?page=crew">her</a>.</p>
        <div class="form-group">
            <h4>Crew</h4>
            <select class="form-control" name="groupId">
EOD;
            foreach ($groupList as $group) {
                $content .= '<option value="' . $group->getId() . '">' . $group->getTitle() . '</option>';
            }
            $content .= <<<EOD
            </select>
        </div>
        <div class="form-group">
            <h4>Søknad</h4>
            <textarea name="content" class="form-control" rows="10" cols="80" placeholder="Skriv en kort oppsummering av hvorfor du vil søke her."></textarea>
            </select>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
EOD;
        } else {
            $content = <<<EOD

<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Feilmelding</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form role="form">
    <div class="box-body">
        <label>Du må laste opp en avatar før du kan søke. Dette kan du gjøre <a href="?page=edit-avatar">her.</a></label>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
EOD;

        }

        
		return $content;
	}
}