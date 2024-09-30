import { useState, useCallback } from 'react';
import { AxiosError } from 'axios';

interface ApiState<T> {
  data: T | null;
  loading: boolean;
  error: string | null;
}

type ApiFunction<T, P extends any[]> = (...args: P) => Promise<T>;

export function useApi<T, P extends any[]>(apiFunc: ApiFunction<T, P>) {
  const [state, setState] = useState<ApiState<T>>({
    data: null,
    loading: false,
    error: null,
  });

  const execute = useCallback(async (...args: P) => {
    setState({ data: null, loading: true, error: null });
    try {
      const data = await apiFunc(...args);
      setState({ data, loading: false, error: null });
    } catch (error) {
      setState({ data: null, loading: false, error: (error as AxiosError).message });
    }
  }, [apiFunc]);

  return { ...state, execute };
}