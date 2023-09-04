import React from 'react'
import {
  useNavigate,
  useParams
} from 'react-router-dom'
import CampusView from '../CampusView'
import Loadable from '../../../../App/Helper/Loadable'
import { submitData } from '../../../../App/Helper/Api'

const CampusEditPage = () => {
  const params = useParams()
  const [state, setState] = React.useState({
    loading: false,
    error: null
  })

  const navigate = useNavigate()
  const onSuccess = (response) => {
    navigate(-1)
  }
  const onSubmit = (data) => {
    submitData({
      state,
      setState,
      data,
      onSuccess,
      url: window.abeApp.urls.api_school_campus_edit.replace(':id', params.id),
    })
  }

  return <Loadable
    component={CampusView}
    url={window.abeApp.urls.api_school_campus.replace(':id', params.id)}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
    isUpdate
  />
}

export default CampusEditPage
