import { defineStore } from 'pinia';
import axios from 'axios';

axios.defaults.withCredentials = true;
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,            
    token: null,          
    isLoggedIn: false,     
    error: null,          
    activeCharacter: null,
    guild: null,
    guildRank: null,
  }),

  actions: {
    async initializeSanctum() {
      try {
        await axios.get('http://newapp.test/sanctum/csrf-cookie',{
          withCredentials: true,
        });
      } catch (error) {
        console.error('Failed to initialize Sanctum:', error);
      }
    },
    async loginWithDiscord(code) {
      try {
        await this.initializeSanctum();
        const response = await axios.get(`http://newapp.test/api/auth/discord?code=${code}`);
        console.log('Response Data:', response.data);

        this.token = response.data.access_token
        this.user = response.data.user || null;
        this.isLoggedIn = true;
        this.error = null;

        await this.fetchLastActiveCharacter();

        this.persistAuthData();

        console.log('Login successful:', response.data);
      } catch (error) {
        console.error('Failed to log in with Discord:', error);
        this.error = 'Failed to log in. Please try again.';
      }
    },
    
    async fetchLastActiveCharacter() {
      try {

        const response = await axios.get('http://newapp.test/api/user/last-active-character', {
          headers: {
            Authorization: `Bearer ${this.token}`, 
          },
        });

        if (response.data && response.data.character) {
          this.activeCharacter = {
            id: response.data.character.id,
            name: response.data.character.name,
          };
          this.guild = response.data.character.guild || null;
          this.guildRank = response.data.character.guild_rank || null;
          this.persistAuthData();
        } else {
          this.activeCharacter = null;
          this.guild = null;
          this.guildRank = null;
        }
      } catch (error) {
        console.error('Failed to fetch active character:', error);
        this.activeCharacter = null;
        this.guild = null;
        this.guildRank = null;
      }
    },

    async checkAuth() {
      try {
        const response = await axios.get('http://newapp.test/api/user', {
          headers: {
            Authorization: `Bearer ${this.token}`,
          },
        });
        this.user = response.data;
        this.isLoggedIn = true;
      } catch (error) {
        this.user = null;
        this.isLoggedIn = false;
        console.error('Authentication check failed:', error);
      }
    },

    async logout() {
      try {
        this.user = null;
        this.isLoggedIn = false;
        this.error = null;
        this.activeCharacter = null;
        this.guild = null;
        this.guildRank = null;
        localStorage.removeItem('auth');
        console.log('User logged out');
      } catch (error) {
        console.error('Failed to log out:', error);
      }
    },


    rehydrateAuth() {
      const savedAuth = JSON.parse(localStorage.getItem('auth'));
      if (savedAuth && savedAuth.token) {
        this.$patch({
          user: savedAuth.user || null,
          token: savedAuth.token,
          activeCharacter: savedAuth.activeCharacter || null,
          guild: savedAuth.guild || null,
          guildRank: savedAuth.guildRank || null,
          isLoggedIn: true,
        });
      } else {

        this.$reset();
      }
    },
    
    persistAuthData() {
      const authData = {
        user: this.user,
        token: this.token,
        activeCharacter: this.activeCharacter,
        guild: this.guild,
        guildRank: this.guildRank,
      };
      localStorage.setItem('auth', JSON.stringify(authData));
    },
  },

  setUser(user) {
    this.user = user;
  },

});


export function initializeAuth() {
  const authStore = useAuthStore();
  authStore.rehydrateAuth();
}