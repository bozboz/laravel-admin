import boztime from '../../api/media';

const state = {
  file: {},
};

const mutations = {
  setClients(state, { clients }) {
    state.clients = clients;
  },
  setProjects(state, { projects }) {
    state.projects = projects;
  },
  setTickets(state, { tickets }) {
    state.tickets = tickets;
  },
};

const actions = {
  async getTickets({ commit }) {
    const data = await boztime.getTickets(103);
    commit('setClients', data);
    commit('setProjects', data);
    commit('setTickets', data);
  },
};

export default {
  state,
  mutations,
  actions,
};
