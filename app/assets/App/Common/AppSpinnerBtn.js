import React from 'react'
import { CSpinner } from '@coreui/react'

const AppSpinnerBtn = () => {
  return <CSpinner data-testid="btn-loader"
    className="me-1" component="span" size="sm" aria-hidden="true"/>
}

export default React.memo(AppSpinnerBtn)
