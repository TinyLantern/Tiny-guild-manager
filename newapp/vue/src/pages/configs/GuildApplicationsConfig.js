export const applicationsColumns = [
    { name: 'discord', label: 'Discord', align: 'center', field: (row) => row.discord },
    { name: 'character', label: 'Character', align: 'center', field: (row) => row.character },
    { name: 'role', label: 'Role', align: 'center', field: (row) => row.role },
    { name: 'gear', label: 'Gear score', align: 'center', field: (row) => row.gear },
    { name: 'appliedOn', label: 'Applied on', align: 'center', field: (row) => row.appliedOn },
    { name: 'lastUpdated', label: 'Last updated', align: 'center', field: (row) => row.lastUpdated },
    { name: 'status', label: 'Status', align: 'center', field: (row) => row.status },
    {
      name: 'actions',
      label: '',
      align: 'center',
      field: '__slot:actions',
      style: 'width: 150px;',
    },
  ];
  
  export const applicationsPagination = {
    rowsPerPage: 30,
  };