import * as LoadState from "./LoadState";
import axios from "axios";

export function loadData(url, setState) {
  return () => {
    setState(LoadState.startLoading())
    axios.get(url)
      .then((response) => {
        setState(LoadState.finishLoading(response.data))
      })
      .catch((error) => {
        setState(LoadState.error(error.response?.data?.error))
      })
  }
}
