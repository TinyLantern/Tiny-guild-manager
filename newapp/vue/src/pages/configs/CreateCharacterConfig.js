import FormInput from 'src/components/Forms/FormInput.vue';
import FormSelect from 'src/components/Forms/FormSelect.vue';

export const createCharacterFormFields = (weaponOptions, characterTypeOptions) => [
  {
    component: FormInput,
    model: 'characterName',
    props: {
      label: 'Character Name',
      rules: [
        val => !!val || 'Character Name is required',
        val => (val && val.length <= 16) || 'Character Name must be 16 characters or less',
      ],
    },
  },
  {
    component: FormSelect,
    model: 'role',
    props: {
      label: 'Role',
      options: characterTypeOptions,
      optionLabel: 'type',
      optionValue: 'id',
      rules: [val => !!val || 'Role is required'],
    },
  },
  {
    component: FormSelect,
    model: 'primaryWeapon',
    props: {
      label: 'Primary Weapon',
      options: weaponOptions,
      optionLabel: 'name',
      optionValue: 'id',
      rules: [val => !!val || 'Primary Weapon is required'],
    },
  },
  {
    component: FormSelect,
    model: 'secondaryWeapon',
    props: {
      label: 'Secondary Weapon',
      options: weaponOptions,
      optionLabel: 'name',
      optionValue: 'id',
      rules: [val => !!val || 'Secondary Weapon is required'],
    },
  },
  {
    component: FormInput,
    model: 'gearScore',
    props: {
      label: 'Gear Score',
      type: 'text',
      rules: [
        val => val === null || val === '' || /^\d+$/.test(val) || 'Gear Score must be a positive number',
      ],
    },
  },
];