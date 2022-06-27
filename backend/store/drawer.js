export const state = () => ({
  hamburger: true
})

export const mutations = {
  changeHamburger (state) {
    state.hamburger = !state.hamburger
  }
}
