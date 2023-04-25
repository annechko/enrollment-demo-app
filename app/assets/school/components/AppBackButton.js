import React from 'react'
import {CButton} from '@coreui/react'
import {useNavigate} from "react-router-dom";

const AppBackButton = () => {
  const navigate = useNavigate();
  return (
    <>
      <CButton color="primary" role="button" className="mb-3"
        onClick={() => navigate(-1)}>
        Go back
      </CButton>
      <br/>
    </>
  )
}

export default React.memo(AppBackButton)
