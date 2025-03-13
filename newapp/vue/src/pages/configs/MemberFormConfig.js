import FormInput from 'src/components/Forms/EditableFormInput.vue';
import FormSelect from 'src/components/Forms/FormSelect.vue';
import FormButton from 'src/components/Forms/FormButton.vue';

export const createMemberFormFields = (roleOptions, weaponOptions, guildRankOptions) => [
  {
    component: FormInput,
    model: 'character_name',
    props: {
      label: 'Character name',
      rules: [val => !!val || 'Character Name is required', 
      val => (val && val.length <= 16) || 'Character Name must be 16 characters or less',],
    },
  },
  {
    component: FormSelect,
    model: 'role',
    props: {
      label: 'Role',
      options: roleOptions,
      optionLabel: 'type',
      optionValue: 'id',
      rules: [val => !!val || 'Role is required'],
    },
  },
  {
    component: FormSelect,
    model: 'primary_weapon',
    props: {
      label: 'Primary weapon',
      options: weaponOptions,
      optionLabel: 'name',
      optionValue: 'id',
      rules: [val => !!val || 'Primary Weapon is required'],
    },
  },
  {
    component: FormSelect,
    model: 'secondary_weapon',
    props: {
      label: 'Secondary weapon',
      options: weaponOptions,
      optionLabel: 'name',
      optionValue: 'id',
      rules: [val => !!val || 'Secondary Weapon is required'],
    },
  },
  {
    component: FormInput,
    model: 'gear_score',
    props: {
      label: 'Gear score',
      rules: [
        val => !!val || 'Gear Score is required',
        val => /^\d+$/.test(val) || 'Gear Score must be a positive number',
      ],
    },
  },
  {
    component: FormSelect,
    model: 'rank',
    props: {
      label: 'Guild rank',
      options: guildRankOptions,
      optionLabel: 'rank',
      optionValue: 'id',
      rules: [val => !!val || 'Guild Rank is required'],
    },
  },
];


export const createMemberFormButtons = (
  isEditMode,
  isActiveCharacter,
  isGuildMasterOrAdvisor,
  handleEditSave,
  deleteCharacter,
  removeFromGuild
) => [
  {
    component: FormButton,
    props: {
      label: isEditMode ? 'Save' : 'Edit',
      color: isEditMode ? 'green' : 'primary',
      onClick: handleEditSave,
      condition: true,
    },
  },
  {
    component: FormButton,
    props: {
      label: 'Delete Character',
      color: 'negative',
      onClick: deleteCharacter,
      condition: isActiveCharacter,
    },
  },
  {
    component: FormButton,
    props: {
      label: 'Remove from Guild',
      color: 'orange',
      onClick: removeFromGuild,
      condition: isGuildMasterOrAdvisor,
    },
  },
];