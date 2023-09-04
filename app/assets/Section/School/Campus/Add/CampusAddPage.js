import React, { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import CampusView from '../CampusView'
import { submitData } from '../../../../App/Helper/Api'

const CampusAddPage = () => {
  const navigate = useNavigate()
  const [state, setState] = useState({
    loading: false,
    error: null
  })

  const onSuccess = (response) => {
    navigate(-1)
  }

  const onSubmit = (data) => {
    submitData({
      state,
      setState,
      url: window.abeApp.urls.api_school_campus_add,
      data,
      onSuccess,
    })
  }
  return <CampusView onSubmit={onSubmit}
    submitError={state.error}
    isSubmitted={state.loading}
  />
}

export default CampusAddPage
