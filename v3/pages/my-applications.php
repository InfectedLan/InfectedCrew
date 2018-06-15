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

class ApplicationListPage extends Page {
    public function canAccess(User $user): bool {
        return !$user->isGroupMember();
    }
    public function isPublic(): bool {
        return false;
    }
	public function getTitle(): ?string {
		return 'Dine søknader';
	}

    public function getContent(User $user = null): string {
        $content = null;

        $applications = ApplicationHandler::getUserApplications($user);

            if(!empty($applications)) {
                $content = <<<EOD
<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Søknader</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
    <div class="box-body">
        
    </div>
</div>
EOD;
            } else {
                $content = <<<EOD
<div class="box box-warning">
<div class="box-header with-border">
  <h3 class="box-title">Ingen søknader</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
    <div class="box-body">
        <p>Du har ikke søkt crew for dette året enda. Gå <a href="?page=new-application">hit</a> for å søke.</p>
    </div>
</div>
EOD;
            }

        
		return $content;
	}
}