// src/components/InfiniteScroll.tsx

import React, { useEffect, useRef, ReactNode } from 'react';

interface InfiniteScrollProps {
  loadMore: () => void;
  hasMore: boolean;
  isLoading: boolean;
  children: ReactNode;
  loader?: ReactNode;
  endMessage?: ReactNode;
}

const InfiniteScroll: React.FC<InfiniteScrollProps> = ({
  loadMore,
  hasMore,
  isLoading,
  children,
  loader = <div>Chargement...</div>,
  endMessage = <div>Fin des résultats</div>,
}) => {
  const observerTarget = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting && hasMore && !isLoading) {
          loadMore();
        }
      },
      { threshold: 1.0 }
    );

    if (observerTarget.current) {
      observer.observe(observerTarget.current);
    }

    return () => {
      if (observerTarget.current) {
        observer.unobserve(observerTarget.current);
      }
    };
  }, [loadMore, hasMore, isLoading]);

  return (
    <div>
      {children}
      {isLoading && loader}
      {!isLoading && !hasMore && endMessage}
      <div ref={observerTarget} style={{ height: '1px' }} />
    </div>
  );
};

export default InfiniteScroll;