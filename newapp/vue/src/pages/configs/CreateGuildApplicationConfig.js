import FormSelect from 'src/components/Forms/FormSelect.vue';

export const createGuildApplicationFormFields = (serverOptions, guildOptions, characterOptions) => [
  {
    component: FormSelect,
    model: 'selectedServer',
    props: {
      label: 'Select a Server',
      options: serverOptions,
      optionLabel: 'label',
      optionValue: 'value',
      rules: [val => !!val || 'Server is required'],
    },
    condition: () => true,
  },
  {
    component: FormSelect,
    model: 'selectedGuild',
    props: {
      label: 'Select a Guild',
      options: guildOptions,
      optionLabel: 'label',
      optionValue: 'value',
      rules: [val => !!val || 'Guild is required'],
    },
    condition: (form) => !!form.selectedServer,
  },
  {
    component: FormSelect,
    model: 'selectedCharacter',
    props: {
      label: 'Select a Character',
      options: characterOptions,
      optionLabel: 'label',
      optionValue: 'value',
      rules: [val => !!val || 'Character is required'],
    },
    condition: (form) => !!form.selectedGuild,
  },
];