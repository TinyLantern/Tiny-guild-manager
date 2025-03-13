import FormInput from 'src/components/Forms/FormInput.vue';
import FormSelect from 'src/components/Forms/FormSelect.vue';

export const createGuildFormFields = (serverOptions, characterOptions) => [
  {
    component: FormInput,
    model: 'guildName',
    props: {
      label: 'Guild Name',
      rules: [val => !!val || 'Guild Name is required',
        val => (val && val.length <= 15) || 'Guild Name must be 15 characters or less',
      ],
      
    },
  },
  {
    component: FormSelect,
    model: 'server',
    props: {
      label: 'Server',
      options: serverOptions,
      optionLabel: 'label',
      optionValue: 'value',
      rules: [val => !!val || 'Server is required'],
    },
  },
  {
    component: FormSelect,
    model: 'character',
    props: {
      label: 'Character',
      options: characterOptions,
      optionLabel: 'label',
      optionValue: 'value',
      rules: [val => !!val || 'Character is required'],
    },
  },
];