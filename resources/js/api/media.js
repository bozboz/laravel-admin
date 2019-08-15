export default {
  async index({
    type = 'image',
    page = 1,
    folderId = null,
  } = {}) {
    try {
      return await (await window.axios.get('/admin/files/' + type, {
        params: {
          page: (page || ''),
          folderId: (folderId || '')
        },
      })).data;
    } catch (error) {
      console.log(error); // eslint-disable-line
      return error;
    }
  },
};
