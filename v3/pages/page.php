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

require_once 'interfaces/ipage.php';

class Page implements IPage {
    public function isPublic(): bool {
        return false;
    }

    public function canAccess(User $user): bool {
        return false;
    }

    public function hasParent(): bool {
		return false;
	}

	public function getParent(): IPage {
		$class = get_parent_class($this);

		if (!empty($class)) {
			return new $class();
		}

		return $this;
	}

	public function getTitle(): ?string {
		return null;
	}

	public function getContent(User $user = null): ?string {
		return null;
	}
}
