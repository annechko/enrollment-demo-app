import { CFooter } from '@coreui/react'
import React from 'react'
import CIcon from '@coreui/icons-react'
import {
  cibGithub,
  cibLinkedin
} from '@coreui/icons'

const AppFooter = () => {
  return (
    <CFooter>
      <div>
        Enrollment Demo App
        <span className="ms-1">
          <a href="https://github.com/annechko/enrollment-demo-app"
            target="_blank" className="text-decoration-none app-icon-link" rel="noreferrer"
          ><CIcon icon={cibGithub} size="xl" className="mx-2"/></a>
          2023 by Anna Borzenko</span>
        <a href="https://www.linkedin.com/in/anna-borzenko/"
          target="_blank" className="text-decoration-none" rel="noreferrer"
        >
          <CIcon icon={cibLinkedin} size="xl" className="mx-3"/>
        </a>
        (thanks to CoreUI)
      </div>

    </CFooter>
  )
}

export default React.memo(AppFooter)
