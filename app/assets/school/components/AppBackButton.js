import {CButton} from '@coreui/react'
import React from 'react'
import {useNavigate} from "react-router-dom";
import CIcon from "@coreui/icons-react";
import {cilArrowLeft} from "@coreui/icons";

const AppBackButton = () => {
  const navigate = useNavigate();
  return (
    <>
      <CButton color="dark" role="button" className="mb-3"
        variant="outline"
        onClick={() => navigate(-1)}>
        <CIcon icon={cilArrowLeft} />
      </CButton>
      <br/>
    </>
  )
}

export default React.memo(AppBackButton)
