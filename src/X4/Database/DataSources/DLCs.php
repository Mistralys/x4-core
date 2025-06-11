<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\DataSources;

class DLCs
{
    public const DLC_BORON = 'ego_dlc_boron';
    public const DLC_HYPERION = 'ego_dlc_mini_01';
    public const DLC_PIRATE = 'ego_dlc_pirate';
    public const DLC_SPLIT = 'ego_dlc_split';
    public const DLC_TERRAN = 'ego_dlc_terran';
    public const DLC_TIMELINES = 'ego_dlc_timelines';

    public const DLCS = array(
        self::DLC_BORON,
        self::DLC_HYPERION,
        self::DLC_PIRATE,
        self::DLC_SPLIT,
        self::DLC_TERRAN,
        self::DLC_TIMELINES
    );
}
