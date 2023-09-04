import React from 'react'
import { CSpinner } from '@coreui/react'

const AppDataLoader = () => {
  return <CSpinner data-testid="data-loader"
    className="me-1" component="span" size="sm" aria-hidden="true"/>
}

export default React.memo(AppDataLoader)
