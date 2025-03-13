export const weaponIcons = {
    'Sword and Shield': '/src/assets/weapons/sns.webp',
    'Spear': '/src/assets/weapons/spear.webp',
    'Greatsword': '/src/assets/weapons/gs.webp',
    'Staff': '/src/assets/weapons/staff.webp',
    'Wand and Tome': '/src/assets/weapons/wand.webp',
    'Longbow': '/src/assets/weapons/bow.webp',
    'Crossbows': '/src/assets/weapons/xbow.webp',
    'Daggers': '/src/assets/weapons/daggers.webp',
  };
  
  export const membersColumns = [
    { name: 'username', label: 'Username', align: 'center', field: (row) => row.username, sortable: true },
    { name: 'role', label: 'Role', align: 'center', field: (row) => row.role, sortable: true },
    { name: 'weapons', label: 'Weapons', align: 'center', field: (row) => [row.primaryWeapon, row.secondaryWeapon], sortable: true },
    { name: 'gear', label: 'Gear score', align: 'center', field: (row) => row.gear_score, sortable: true },
    { name: 'rank', label: 'Guild rank', align: 'center', field: (row) => row.guild_rank, sortable: true },
    { name: 'dkpBalance', label: 'DKP Balance', align: 'center', field: (row) => row.dkp_points, sortable: true },
  ];
  
  export const membersPagination = {
    rowsPerPage: 70,
  };