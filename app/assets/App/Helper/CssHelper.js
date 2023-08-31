import { useContext } from 'react'
import { CurrentSectionContext } from './Context/CurrentSectionContext'

class CssHelper {
  static getCurrentSectionBgColor () {
    const currentSection = useContext(CurrentSectionContext)
    return this.getSectionBgColor(currentSection)
  }

  static getSectionTextColor (section) {
    return 'app-text-' + section
  }

  static getSectionBgColor (section) {
    return 'app-bg-' + section
  }
}

export { CssHelper }
