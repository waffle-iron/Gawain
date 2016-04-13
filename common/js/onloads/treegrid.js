/*
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Generates the treegrids
 */

$(function () {
    $('.gawain-treegrid').treegrid({
        initialState: 'collapsed',
        expanderTemplate: '<i class="treegrid-expander"></i> ',
        expanderExpandedClass: 'fa fa-minus-square',
        expanderCollapsedClass: 'fa fa-plus-square'
    });
});
