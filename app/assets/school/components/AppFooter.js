import {CFooter} from '@coreui/react'
import React from 'react'

const AppFooter = () => {
  return (
    <CFooter>
      <div>
        Demo Enroller App
        <span className="ms-1">&copy; 2023 Anna Borzenko (thanks to CoreUI)</span>
      </div>

    </CFooter>
  )
}

export default React.memo(AppFooter)
