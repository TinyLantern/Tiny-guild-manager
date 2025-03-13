const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('src/pages/IndexPage.vue') },
      { path: 'dashboard', component: () => import('src/pages/DashboardPage.vue'), meta: { icon: 'dashboard', label: 'Dashboard' } },
      { path: 'members', component: () => import('pages/MembersPage.vue'), meta: { icon: 'groups', label: 'Members' } },
      { path: 'login', component: () => import('pages/DiscordLogin.vue') },
      { path: 'parties', component: () => import('pages/PartiesPage.vue'), meta: { icon: 'groups', label: 'Parties' } },
      { path: 'applications', component: () => import('pages/GuildApplicationsPage.vue'), meta: { icon: 'description', label: 'Applications' } },
      { path: 'createcharacter', component: () => import('pages/CreateCharacterPage.vue'), meta: { icon: 'person_add', label: 'Create Character' } },
      { path: 'member/:id', name: 'Member', component: () => import('pages/MemberPage.vue'), props: true },
      { path: 'guildapplication', component: () => import('pages/CreateGuildApplicationPage.vue'), meta: { icon: 'list_alt', label: 'Guild application' } },
      { path: 'createguild', component: () => import('pages/CreateGuildPage.vue'), meta: { icon: 'group_add', label: 'Create guild' } },
      { path: 'activities', component: () => import('pages/ActivitiesPage.vue'), meta: { icon: 'event', label: 'Activities' } },
      { path: 'activity/:id', component: () => import('pages/ActivityDetailsPage.vue') },
    ]
  },

  // Always leave this as the last one for handling 404 errors
  {
    path: '/:catchAll(.*)',
    component: () => import('pages/ErrorNotFound.vue')
  }
];

export default routes;
